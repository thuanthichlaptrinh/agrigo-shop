/**
 * Chat WebSocket Client
 * Xử lý real-time chat giữa khách hàng và admin
 */

class ChatWebSocket {
    constructor(conversationId, options = {}) {
        this.conversationId = conversationId;
        this.options = {
            onMessage: () => {},
            onTyping: () => {},
            onConversationUpdate: () => {},
            onConnected: () => {},
            onError: () => {},
            ...options
        };
        
        this.channel = null;
        this.typingTimeout = null;
    }

    /**
     * Kết nối đến channel chat
     */
    connect() {
        if (!window.Echo) {
            console.error('Laravel Echo chưa được khởi tạo');
            this.options.onError('WebSocket chưa sẵn sàng');
            return;
        }

        // Subscribe vào private channel của cuộc hội thoại
        this.channel = window.Echo.private(`chat.conversation.${this.conversationId}`);

        // Lắng nghe tin nhắn mới
        this.channel.listen('.message.new', (data) => {
            console.log('New message received:', data);
            this.options.onMessage(data.message);
        });

        // Lắng nghe trạng thái đang gõ
        this.channel.listen('.user.typing', (data) => {
            console.log('User typing:', data);
            this.options.onTyping(data);
        });

        // Thông báo đã kết nối
        this.channel.subscribed(() => {
            console.log('Connected to chat channel:', this.conversationId);
            this.options.onConnected();
        });

        this.channel.error((error) => {
            console.error('Channel error:', error);
            this.options.onError(error);
        });
    }

    /**
     * Ngắt kết nối
     */
    disconnect() {
        if (this.channel) {
            window.Echo.leave(`chat.conversation.${this.conversationId}`);
            this.channel = null;
        }
    }

    /**
     * Gửi trạng thái đang gõ
     */
    sendTyping(isTyping = true) {
        // Debounce typing events
        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }

        fetch('/api/v1/chat/typing', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                conversation_id: this.conversationId,
                is_typing: isTyping,
            }),
        }).catch(console.error);

        // Tự động gửi stop typing sau 3 giây
        if (isTyping) {
            this.typingTimeout = setTimeout(() => {
                this.sendTyping(false);
            }, 3000);
        }
    }
}

/**
 * Admin Chat WebSocket Client
 * Lắng nghe tất cả cuộc hội thoại mới
 */
class AdminChatWebSocket {
    constructor(options = {}) {
        this.options = {
            onNewConversation: () => {},
            onConversationUpdate: () => {},
            onNewMessage: () => {},
            onConnected: () => {},
            onError: () => {},
            ...options
        };
        
        this.adminChannel = null;
        this.conversationChannels = new Map();
    }

    /**
     * Kết nối đến admin channel
     */
    connect() {
        if (!window.Echo) {
            console.error('Laravel Echo chưa được khởi tạo');
            this.options.onError('WebSocket chưa sẵn sàng');
            return;
        }

        // Subscribe vào admin channel để nhận tất cả tin nhắn mới
        this.adminChannel = window.Echo.private('chat.admin');

        // Lắng nghe tin nhắn mới từ tất cả cuộc hội thoại
        this.adminChannel.listen('.message.new', (data) => {
            console.log('Admin received new message:', data);
            this.options.onNewMessage(data.message);
        });

        // Lắng nghe cập nhật cuộc hội thoại
        this.adminChannel.listen('.conversation.updated', (data) => {
            console.log('Conversation updated:', data);
            this.options.onConversationUpdate(data.conversation, 'updated');
        });

        this.adminChannel.listen('.conversation.closed', (data) => {
            console.log('Conversation closed:', data);
            this.options.onConversationUpdate(data.conversation, 'closed');
        });

        this.adminChannel.subscribed(() => {
            console.log('Connected to admin chat channel');
            this.options.onConnected();
        });

        this.adminChannel.error((error) => {
            console.error('Admin channel error:', error);
            this.options.onError(error);
        });
    }

    /**
     * Subscribe vào một cuộc hội thoại cụ thể
     */
    subscribeToConversation(conversationId, callbacks = {}) {
        if (this.conversationChannels.has(conversationId)) {
            return;
        }

        const channel = window.Echo.private(`chat.conversation.${conversationId}`);
        
        channel.listen('.message.new', (data) => {
            if (callbacks.onMessage) {
                callbacks.onMessage(data.message);
            }
        });

        channel.listen('.user.typing', (data) => {
            if (callbacks.onTyping) {
                callbacks.onTyping(data);
            }
        });

        this.conversationChannels.set(conversationId, channel);
    }

    /**
     * Unsubscribe khỏi một cuộc hội thoại
     */
    unsubscribeFromConversation(conversationId) {
        if (this.conversationChannels.has(conversationId)) {
            window.Echo.leave(`chat.conversation.${conversationId}`);
            this.conversationChannels.delete(conversationId);
        }
    }

    /**
     * Gửi trạng thái đang gõ (admin)
     */
    sendTyping(conversationId, isTyping = true) {
        fetch('/admin/chat/typing', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                conversation_id: conversationId,
                is_typing: isTyping,
            }),
        }).catch(console.error);
    }

    /**
     * Ngắt kết nối
     */
    disconnect() {
        if (this.adminChannel) {
            window.Echo.leave('chat.admin');
            this.adminChannel = null;
        }

        // Unsubscribe từ tất cả cuộc hội thoại
        this.conversationChannels.forEach((_, conversationId) => {
            window.Echo.leave(`chat.conversation.${conversationId}`);
        });
        this.conversationChannels.clear();
    }
}

// Export cho sử dụng
window.ChatWebSocket = ChatWebSocket;
window.AdminChatWebSocket = AdminChatWebSocket;

export { ChatWebSocket, AdminChatWebSocket };
