<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;    
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-Api-Token');
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get('X-Api-Token');
        if (!$apiToken) throw new CustomUserMessageAuthenticationException('No API token provided');
        if (!$this->validateToken($request, $apiToken)) throw new CustomUserMessageAuthenticationException('Invalid token');

        return new SelfValidatingPassport(
            new UserBadge($apiToken, function($apiToken) {
                $user = $this->userRepository->findByApiToken($apiToken);
                if (!$user) throw new UserNotFoundException();

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    private function validateToken(Request $request, string $apiToken): bool
    {
        return true;
    }
}