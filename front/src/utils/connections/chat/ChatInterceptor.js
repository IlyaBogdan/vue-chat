/**
 * This class intercepts WebSocket messages and reacts on them
 */

import { WsMessageInterceptor } from '../WsMessageInterceptor';
import { ChatConnection } from './ChatConnection';

export class ChatInterceptor extends WsMessageInterceptor {

    api(self) {
        return {
            pull: () => {
                new ChatConnection().call('pull', { user: self.user });
            },
            setUser: (body) => {
                self.user = body.user;
                localStorage.setItem('user', JSON.stringify(body.user));
            },
            setUserList: (body) => {
                self.userList = body.users.filter((user) => user.id != self?.user.id);
            },
            activeChat: (body) => {
                self.chat = body.chat;

                body.chat.messages.forEach((message) => {
                    if (message.author.id == self.user.id) message.type = 'out';
                    else message.type = 'in';
                });

                self.$router.push(`/dialog?id=${self.chat.id}`);
            },
            userDialogs: (body) => {
                self.dialogs = body.chats;
            }
        }
    }
}