<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    /**
    * @return Chat[] Returns an array of Chat objects
    */
    public function findByUserId(int $userId): array
    {
        // return $this->createQueryBuilder('c')
        //     ->andWhere('c.exampleField = :val')
        //     ->setParameter('val', $value)
        //     ->orderBy('c.id', 'ASC')
        //     ->getQuery()
        //     ->getResult();

        return $this->findAll();            
    }

    public function findByUsers(array $userIds): ?Chat
    {
        $entityManager = $this->getEntityManager();

        $ids = implode(', ', $userIds);
        $count = count($userIds);
        $query = $entityManager->createQuery(
            'SELECT chat
             FROM App\Entity\Chat chat
             JOIN chat.users u
             WHERE u.id IN (' . $ids . ')
             GROUP BY chat.id
             HAVING COUNT(DISTINCT u.id) = :count
            '
        )->setParameter('count', $count);

        return $query->getResult()[0];
    }
}
