class AdminChatWidget {
    constructor(config = {}) {
        this.config = {
            chatApiUrl: config.chatApiUrl || "/api/v1/chat",
            adminAvatar:
                config.adminAvatar || "template/Assets/Images/admin-avatar.png",
            userAvatar:
                config.userAvatar ||
                "template/Assets/Images/default-avatar.png",
            welcomeText:
                config.welcomeText ||
                "Xin chào! Bạn đang được kết nối với bộ phận hỗ trợ.",
        };

        // state
        this.isOpen = false;
        this.conversationId = null;
        this.messages = [];
        this.isSending = false;
        this.adminInfo = null;
        this.isConnected = false;
        this.typingTimeout = null;
        this.isAdminTyping = false;
        this.pollingInterval = null;
        this.channel = null;

        // DOM
        this.widget = document.getElementById("adminChatWidget");
        this.toggleBtn = document.getElementById("adminChatToggle");
        this.container = document.getElementById("adminChatContainer");
        this.body = document.getElementById("adminChatBody");
        this.input = document.getElementById("adminChatInput");
        this.sendBtn = document.getElementById("adminChatSend");
        this.minimizeBtn = document.getElementById("adminChatMinimize");
        this.icon = document.getElementById("adminChatIcon");
        this.iconImg = document.getElementById("adminChatIconImg");
        this.titleEl = document.getElementById("adminChatTitle");

        this.csrfToken = this.getCsrfToken();

        this.init();
    }

    init() {
        this.bindEvents();
        this.loadConversation();
    }

    bindEvents() {
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener("click", () => this.toggle());
        }

        if (this.minimizeBtn) {
            this.minimizeBtn.addEventListener("click", () => this.toggle());
        }

        if (this.sendBtn) {
            this.sendBtn.addEventListener("click", () => this.handleSend());
        }

        if (this.input) {
            this.input.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    this.handleSend();
                }
            });

            // Gửi trạng thái đang gõ
            this.input.addEventListener("input", () => {
                this.sendTypingStatus(true);
            });
        }
    }

    toggle() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            this.container?.classList.add("active");
            if (this.icon) this.icon.style.display = "block";
            if (this.iconImg) this.iconImg.style.display = "none";
            this.input?.focus();
            // Bắt đầu polling khi mở chat
            if (!this.isConnected) {
                this.startPolling();
            }
        } else {
            this.container?.classList.remove("active");
            if (this.icon) this.icon.style.display = "none";
            if (this.iconImg) this.iconImg.style.display = "block";
        }
    }

    /**
     * Kết nối WebSocket cho cuộc hội thoại
     */
    connectWebSocket() {
        if (!window.Echo || !this.conversationId) {
            console.warn('WebSocket không khả dụng, sử dụng polling fallback');
            this.startPolling();
            return;
        }

        try {
            // Subscribe vào private channel của cuộc hội thoại
            this.channel = window.Echo.private(`chat.conversation.${this.conversationId}`);

            // Lắng nghe tin nhắn mới
            this.channel.listen('.message.new', (data) => {
                console.log('WebSocket: Tin nhắn mới', data);
                this.handleNewMessage(data.message);
            });

            // Lắng nghe trạng thái đang gõ
            this.channel.listen('.user.typing', (data) => {
                console.log('WebSocket: Đang gõ', data);
                this.handleTypingStatus(data);
            });

            this.channel.subscribed(() => {
                console.log('WebSocket: Đã kết nối channel', this.conversationId);
                this.isConnected = true;
                this.updateConnectionStatus(true);
                // Dừng polling khi WebSocket kết nối thành công
                this.stopPolling();
            });

            this.channel.error((error) => {
                console.error('WebSocket error:', error);
                this.isConnected = false;
                this.updateConnectionStatus(false);
                // Fallback to polling
                this.startPolling();
            });

        } catch (err) {
            console.error('WebSocket connection error:', err);
            this.startPolling();
        }
    }

    /**
     * Ngắt kết nối WebSocket
     */
    disconnectWebSocket() {
        if (this.channel && window.Echo) {
            window.Echo.leave(`chat.conversation.${this.conversationId}`);
            this.channel = null;
        }
        this.isConnected = false;
        this.stopPolling();
    }

    /**
     * Xử lý tin nhắn mới từ WebSocket
     */
    handleNewMessage(message) {
        // Kiểm tra tin nhắn đã tồn tại chưa
        const exists = this.messages.some(m => m.ID === message.id || m.ID === message.ID);
        if (exists) return;

        // Chuyển đổi format từ WebSocket sang format hiện tại
        const formattedMessage = {
            ID: message.id || message.ID,
            IDCuocHoiThoai: message.conversation_id || message.IDCuocHoiThoai,
            IDNguoiGui: message.sender_id || message.IDNguoiGui,
            LoaiNguoiGui: message.sender_type || message.LoaiNguoiGui,
            NoiDung: message.content || message.NoiDung,
            HinhAnh: message.image || message.HinhAnh,
            ThoiGianGui: message.sent_at || message.ThoiGianGui,
            DaXem: message.is_read || message.DaXem,
            nguoi_gui: {
                ID: message.sender_id || message.IDNguoiGui,
                TenNguoiDung: message.sender_name,
                HinhAnh: message.sender_avatar
            }
        };

        this.messages.push(formattedMessage);
        this.renderMessages();

        // Ẩn typing indicator
        this.hideTypingIndicator();

        // Phát âm thanh thông báo nếu tin nhắn từ admin
        if (formattedMessage.LoaiNguoiGui === 'Admin') {
            this.playNotificationSound();
        }
    }

    /**
     * Xử lý trạng thái đang gõ
     */
    handleTypingStatus(data) {
        if (data.userType === 'Admin' || data.user_type === 'Admin') {
            if (data.isTyping || data.is_typing) {
                this.showTypingIndicator(data.userName || data.user_name || 'Admin');
            } else {
                this.hideTypingIndicator();
            }
        }
    }

    /**
     * Hiển thị typing indicator
     */
    showTypingIndicator(name = 'Admin') {
        if (this.isAdminTyping) return;
        this.isAdminTyping = true;

        // Xóa indicator cũ nếu có
        this.hideTypingIndicator();

        const typingEl = document.createElement('div');
        typingEl.className = 'chatbot-message bot typing-indicator';
        typingEl.id = 'typing-indicator';
        typingEl.innerHTML = `
            <img src="${this.config.adminAvatar}" alt="Admin" class="chatbot-message-avatar">
            <div class="chatbot-message-content">
                <div class="chatbot-typing">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
                <div class="chatbot-typing-text">${this.escapeHtml(name)} đang gõ...</div>
            </div>
        `;

        this.body?.appendChild(typingEl);
        this.scrollToBottom();
    }

    /**
     * Ẩn typing indicator
     */
    hideTypingIndicator() {
        this.isAdminTyping = false;
        const indicator = document.getElementById('typing-indicator');
        if (indicator) {
            indicator.remove();
        }
    }

    /**
     * Gửi trạng thái đang gõ
     */
    sendTypingStatus(isTyping = true) {
        if (!this.conversationId) return;

        // Debounce
        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }

        // Gửi typing status
        fetch(`${this.config.chatApiUrl}/typing`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                conversation_id: this.conversationId,
                is_typing: isTyping,
            }),
        }).catch(console.error);

        // Tự động gửi stop typing sau 3 giây
        if (isTyping) {
            this.typingTimeout = setTimeout(() => {
                this.sendTypingStatus(false);
            }, 3000);
        }
    }

    /**
     * Cập nhật trạng thái kết nối
     */
    updateConnectionStatus(connected) {
        const statusDot = this.widget?.querySelector('.chatbot-status-dot');
        const statusText = this.widget?.querySelector('.chatbot-status span:last-child');
        
        if (statusDot) {
            statusDot.style.backgroundColor = connected ? '#22c55e' : '#f59e0b';
        }
        if (statusText) {
            statusText.textContent = connected ? 'Đang online' : 'Đang kết nối...';
        }
    }

    /**
     * Phát âm thanh thông báo
     */
    playNotificationSound() {
        try {
            const audio = new Audio('/template/Assets/sounds/notification.mp3');
            audio.volume = 0.5;
            audio.play().catch(() => {});
        } catch (e) {
            // Ignore audio errors
        }
    }

    /**
     * Polling fallback khi WebSocket không khả dụng
     */
    startPolling() {
        if (this.pollingInterval) return;
        
        console.log('Bắt đầu polling fallback');
        this.pollingInterval = setInterval(() => {
            if (this.conversationId && this.isOpen) {
                this.pollMessages();
            }
        }, 3000); // Poll mỗi 3 giây
    }

    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }

    async pollMessages() {
        if (!this.conversationId) return;
        
        try {
            const response = await fetch(
                `${this.config.chatApiUrl}/messages/${this.conversationId}`,
                {
                    method: "GET",
                    headers: { Accept: "application/json" },
                    credentials: "same-origin",
                }
            );

            if (response.ok) {
                const data = await response.json();
                if (data.success && data.messages) {
                    // Chỉ cập nhật nếu có tin nhắn mới
                    if (data.messages.length > this.messages.length) {
                        const newMessages = data.messages.slice(this.messages.length);
                        newMessages.forEach(msg => {
                            if (!this.messages.some(m => m.ID === msg.ID)) {
                                this.messages.push(msg);
                            }
                        });
                        this.renderMessages();
                    }
                }
            }
        } catch (err) {
            console.error('Polling error:', err);
        }
    }

    async loadConversation() {
        try {
            const response = await fetch(
                `${this.config.chatApiUrl}/conversation`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                    credentials: "same-origin",
                }
            );

            const data = await response.json();
            if (data.success && data.conversation) {
                this.conversationId = data.conversation.ID;
                this.adminInfo = data.conversation.admin || null;
                await this.loadMessages();
                
                // Kết nối WebSocket sau khi có conversation
                this.connectWebSocket();
            } else {
                this.addSystemMessage(
                    "Không thể khởi tạo cuộc hội thoại. Vui lòng thử lại."
                );
            }
        } catch (err) {
            console.error("loadConversation error", err);
            this.addSystemMessage("Không thể kết nối. Vui lòng thử lại.");
        }
    }

    async loadMessages() {
        if (!this.conversationId) return;
        try {
            const response = await fetch(
                `${this.config.chatApiUrl}/messages/${this.conversationId}`,
                {
                    method: "GET",
                    headers: { Accept: "application/json" },
                    credentials: "same-origin",
                }
            );

            if (response.status === 404) {
                await this.resetConversationAndReload();
                return;
            }

            const data = await response.json();
            if (data.success) {
                this.messages = data.messages || [];
                this.adminInfo = data.admin || this.adminInfo;
                if (this.messages.length === 0) {
                    this.addSystemMessage(
                        this.config.welcomeText ||
                            "Chào bạn! Hãy để lại tin nhắn, Admin sẽ phản hồi sớm nhất có thể."
                    );
                } else {
                    this.renderMessages();
                }
            } else {
                this.addSystemMessage(
                    "Không thể tải cuộc hội thoại. Đang thử khởi tạo lại..."
                );
                await this.resetConversationAndReload();
            }
        } catch (err) {
            console.error("loadMessages error", err);
        }
    }

    renderMessages() {
        if (!this.body) return;
        this.body.innerHTML = "";
        this.messages.forEach((msg) => {
            this.body.appendChild(this.buildMessageElement(msg));
        });
        this.scrollToBottom();
    }

    buildMessageElement(msg) {
        const wrapper = document.createElement("div");

        if (msg.LoaiNguoiGui === "HeThong") {
            wrapper.className = "chatbot-message system";
            wrapper.innerHTML = `
                <div class="chatbot-system-message">${this.escapeHtml(
                    msg.NoiDung
                )}</div>
            `;
            return wrapper;
        }

        const isUser = msg.LoaiNguoiGui === "NguoiDung";
        const sender = msg.nguoi_gui || {};
        const avatarUrl = isUser
            ? sender.HinhAnh || this.config.userAvatar
            : sender.HinhAnh || this.config.adminAvatar;
        const senderName = isUser
            ? ""
            : sender.TenNguoiDung || this.adminInfo?.TenNguoiDung || "Admin";

        wrapper.className = `chatbot-message ${isUser ? "user" : "bot"}`;
        let html = "";

        if (!isUser) {
            html += `<img src="${avatarUrl}" alt="Admin" class="chatbot-message-avatar" onerror="this.src='${this.config.adminAvatar}'">`;
        }

        html += '<div class="chatbot-message-content">';

        if (!isUser && senderName) {
            html += `<div class="chatbot-sender-name">${this.escapeHtml(
                senderName
            )}</div>`;
        }

        html += `
            <div class="chatbot-message-bubble">
                ${this.escapeHtml(msg.NoiDung)}
            </div>
            <div class="chatbot-message-time">${this.formatTime(
                msg.ThoiGianGui
            )}</div>
        `;

        if (msg.HinhAnh) {
            html += `<img src="${msg.HinhAnh}" class="chatbot-message-image" alt="">`;
        }

        html += "</div>";

        wrapper.innerHTML = html;
        return wrapper;
    }

    async handleSend() {
        const text = this.input?.value.trim();
        if (!text || this.isSending || !this.conversationId) return;

        this.input.value = "";
        this.isSending = true;

        // Gửi stop typing
        this.sendTypingStatus(false);

        // optimistic
        const tempMsg = {
            ID: "temp-" + Date.now(),
            LoaiNguoiGui: "NguoiDung",
            NoiDung: text,
            ThoiGianGui: new Date().toISOString(),
        };
        this.messages.push(tempMsg);
        this.renderMessages();

        try {
            const response = await fetch(`${this.config.chatApiUrl}/send`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                },
                credentials: "same-origin",
                body: JSON.stringify({
                    conversation_id: this.conversationId,
                    message: text,
                }),
            });

            if (response.status === 404) {
                await this.resetConversationAndReload();
                this.isSending = false;
                await this.handleSend();
                return;
            }

            const data = await response.json();
            if (data.success) {
                const idx = this.messages.findIndex((m) => m.ID === tempMsg.ID);
                if (idx !== -1 && data.message) {
                    this.messages[idx] = data.message;
                }
                this.renderMessages();
            } else {
                this.messages = this.messages.filter(
                    (m) => m.ID !== tempMsg.ID
                );
                this.renderMessages();
                this.addSystemMessage(
                    "Không thể gửi tin nhắn. Vui lòng thử lại."
                );
            }
        } catch (err) {
            console.error("send error", err);
            this.messages = this.messages.filter((m) => m.ID !== tempMsg.ID);
            this.renderMessages();
            this.addSystemMessage("Lỗi kết nối. Vui lòng thử lại.");
        } finally {
            this.isSending = false;
        }
    }

    async resetConversationAndReload() {
        this.disconnectWebSocket();
        this.conversationId = null;
        this.messages = [];
        this.adminInfo = null;
        await this.loadConversation();
    }

    addSystemMessage(text) {
        const msg = {
            ID: Date.now(),
            LoaiNguoiGui: "HeThong",
            NoiDung: text,
            ThoiGianGui: new Date().toISOString(),
        };
        this.messages.push(msg);
        this.renderMessages();
    }

    formatTime(dateString) {
        if (!dateString) return "";
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        if (diff < 60000) return "Vừa xong";
        if (diff < 3600000) return Math.floor(diff / 60000) + " phút";
        if (diff < 86400000) return Math.floor(diff / 3600000) + " giờ";
        return date.toLocaleTimeString("vi-VN", {
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    scrollToBottom() {
        setTimeout(() => {
            if (this.body) this.body.scrollTop = this.body.scrollHeight;
        }, 50);
    }

    escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text || "";
        return div.innerHTML;
    }

    getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute("content") : "";
    }
}

// Bootstrap when DOM ready
(function () {
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof window.adminChatConfig !== "undefined") {
            window.adminChatWidget = new AdminChatWidget(
                window.adminChatConfig
            );
        }
    });
})();
