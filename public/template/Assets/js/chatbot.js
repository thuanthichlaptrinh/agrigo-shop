/**
 * Chatbot Widget
 * A reusable chat widget for customer support
 */

class ChatbotWidget {
    constructor(config) {
        this.config = {
            botName: config.botName || "Support Bot",
            avatarUrl:
                config.avatarUrl ||
                "template/Assets/Images/chatbot/chatbot3.jpg",
            greeting: config.greeting || "Hello! How can I help you?",
            apiUrl: config.apiUrl || "/api/v1/chatbot/query",
            chatApiUrl: config.chatApiUrl || "/api/v1/chat",
        };

        this.isOpen = false;
        this.messages = [];
        this.isSending = false;

        // Chat mode: 'bot' or 'admin'
        this.chatMode = "bot";
        this.conversationId = null;
        this.adminMessages = [];
        this.adminRefreshInterval = null;
        this.adminInfo = null;
        this.adminChannel = null;
        this.isWebSocketConnected = false;
        this.isAdminTyping = false;

        // DOM Elements
        this.widget = document.getElementById("chatbotWidget");
        this.toggleBtn = document.getElementById("chatbotToggle");
        this.container = document.getElementById("chatbotContainer");
        this.body = document.getElementById("chatbotBody");
        this.input = document.getElementById("chatbotInput");
        this.sendBtn = document.getElementById("chatbotSend");
        this.minimizeBtn = document.getElementById("chatbotMinimize");
        this.icon = document.getElementById("chatbotIcon");
        this.iconImg = document.getElementById("chatbotIconImg");
        this.menuToggle = document.getElementById("chatbotMenuToggle");
        this.menu = document.getElementById("chatbotMenu");
        this.launcher = document.getElementById("chatbotLauncher");
        this.launcherBackdrop = document.getElementById(
            "chatbotLauncherBackdrop"
        );
        this.launchAssistantBtn = document.getElementById(
            "chatLaunchAssistant"
        );
        this.launchAdminBtn = document.getElementById("chatLaunchAdmin");
        this.launchZaloBtn = document.getElementById("chatLaunchZalo");
        this.launchContactBtn = document.getElementById("chatLaunchContact");
        this.launcherCloseBtn = document.getElementById("chatLauncherClose");
        this.chatTitle = document.getElementById("chatbotTitle");

        // Contact modal elements
        this.contactModal = document.getElementById("chatContactModal");
        this.contactOverlay = document.getElementById("chatContactOverlay");
        this.contactCloseBtn = document.getElementById("chatContactClose");
        this.contactForm = document.getElementById("chatContactForm");
        this.contactSubmitBtn = document.getElementById("chatContactSubmit");
        this.contactProductInput =
            document.getElementById("chatContactProduct");
        this.contactTitleInput = document.getElementById("chatContactTitle");

        this.csrfToken = this.getCsrfToken();

        this.init();
    }

    init() {
        // Load saved state
        this.loadState();

        // Bind events
        this.bindEvents();

        // Show initial greeting if no messages
        if (this.messages.length === 0) {
            this.showInitialGreeting();
        } else {
            this.renderMessages();
        }

        // Sync auxiliary floating buttons with initial state
        this.updateAuxButtons();
    }

    bindEvents() {
        // Toggle button
        this.toggleBtn.addEventListener("click", () => {
            if (this.isOpen) {
                this.toggleWidget();
            } else if (this.isLauncherOpen()) {
                this.closeLauncher();
            } else {
                this.openLauncher();
            }
        });

        // Minimize button
        this.minimizeBtn.addEventListener("click", () => this.toggleWidget());

        // Contact Admin button
        const contactAdminBtn = document.getElementById("chatbotContactAdmin");
        if (contactAdminBtn) {
            contactAdminBtn.addEventListener("click", () =>
                this.contactAdmin()
            );
        }

        // Send button
        this.sendBtn.addEventListener("click", () => this.handleSend());

        // Enter key in input
        this.input.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                this.handleSend();
            }
        });

        // Emoji button (placeholder functionality)
        const emojiBtn = document.querySelector(".chatbot-emoji-btn");
        if (emojiBtn) {
            emojiBtn.addEventListener("click", () => {
                // Placeholder for emoji picker
                console.log("Emoji picker would open here");
            });
        }

        if (this.menuToggle && this.menu) {
            this.menuToggle.addEventListener("click", (e) => {
                e.stopPropagation();
                this.toggleMenu();
            });

            const clearChatItem = this.menu.querySelector(
                '[data-action="clear-chat"]'
            );
            if (clearChatItem) {
                clearChatItem.addEventListener("click", (e) => {
                    e.preventDefault();
                    this.closeMenu();
                    this.clearChat();
                });
            }

            // Switch mode menu item
            const switchModeItem = this.menu.querySelector(
                '[data-action="switch-mode"]'
            );
            if (switchModeItem) {
                switchModeItem.addEventListener("click", (e) => {
                    e.preventDefault();
                    this.closeMenu();
                    if (this.chatMode === "bot") {
                        this.switchToAdminMode();
                    } else {
                        this.switchToBotMode();
                    }
                });
            }

            document.addEventListener("click", (e) => {
                if (
                    this.menu.classList.contains("open") &&
                    !this.menu.contains(e.target) &&
                    !this.menuToggle.contains(e.target)
                ) {
                    this.closeMenu();
                }
            });
        }

        // Launcher option events
        if (this.launchAssistantBtn) {
            this.launchAssistantBtn.addEventListener("click", () => {
                this.closeLauncher();
                this.switchToBotMode();
                if (!this.isOpen) this.toggleWidget();
            });
        }

        if (this.launchAdminBtn) {
            this.launchAdminBtn.addEventListener("click", () => {
                this.closeLauncher();
                this.switchToAdminMode();
                if (!this.isOpen) this.toggleWidget();
            });
        }

        if (this.launchZaloBtn) {
            this.launchZaloBtn.addEventListener("click", () => {
                this.closeLauncher();
                window.open("https://zalo.me/", "_blank", "noopener");
            });
        }

        if (this.launchContactBtn) {
            this.launchContactBtn.addEventListener("click", () => {
                this.closeLauncher();
                this.openContactForm();
            });
        }

        if (this.launcherBackdrop) {
            this.launcherBackdrop.addEventListener("click", () =>
                this.closeLauncher()
            );
        }

        if (this.contactCloseBtn) {
            this.contactCloseBtn.addEventListener("click", () =>
                this.closeContactForm()
            );
        }

        if (this.contactOverlay) {
            this.contactOverlay.addEventListener("click", () =>
                this.closeContactForm()
            );
        }

        if (this.contactForm) {
            this.contactForm.addEventListener("submit", (e) =>
                this.submitContactForm(e)
            );
        }

        document.addEventListener("click", (e) => {
            const launcherVisible = this.isLauncherOpen();
            if (!launcherVisible) return;

            const clickInsideLauncher =
                this.launcher && this.launcher.contains(e.target);
            const clickToggle =
                this.toggleBtn && this.toggleBtn.contains(e.target);

            if (!clickInsideLauncher && !clickToggle) {
                this.closeLauncher();
            }
        });
    }

    toggleWidget() {
        this.isOpen = !this.isOpen;

        if (this.isOpen) {
            this.container.classList.add("active");
            if (this.icon) this.icon.style.display = "block";
            if (this.iconImg) this.iconImg.style.display = "none";
            this.input.focus();

            // Connect WebSocket if in admin mode
            if (this.chatMode === "admin") {
                this.connectAdminWebSocket();
            }
        } else {
            this.container.classList.remove("active");
            if (this.icon) this.icon.style.display = "none";
            if (this.iconImg) this.iconImg.style.display = "block";
            this.closeMenu();
            this.closeLauncher();
            this.closeContactForm();
            // Don't disconnect WebSocket when minimizing - keep connection alive
        }

        this.updateAuxButtons();
        this.saveState();
    }

    // Admin chat mode methods
    async switchToAdminMode() {
        this.chatMode = "admin";
        this.updateChatTitle();

        // Load or reuse existing conversation
        if (this.conversationId) {
            await this.loadAdminMessages();
        } else {
            await this.loadAdminConversation();
        }

        // Render admin messages
        this.renderAdminMessages();

        // Connect WebSocket for real-time messages
        this.connectAdminWebSocket();

        this.saveState();
    }

    switchToBotMode() {
        this.chatMode = "bot";
        this.updateChatTitle();
        this.disconnectAdminWebSocket();

        // Restore bot messages
        this.renderMessages();

        this.saveState();
    }

    // WebSocket connection for admin chat
    connectAdminWebSocket() {
        // Wait for Echo to be ready
        if (!window.Echo) {
            console.log('Echo chưa sẵn sàng, đợi...');
            // Retry after a short delay
            setTimeout(() => {
                if (window.Echo) {
                    this.connectAdminWebSocket();
                } else {
                    console.warn('WebSocket không khả dụng, sử dụng polling fallback');
                    this.startAdminPolling();
                }
            }, 500);
            return;
        }

        if (!this.conversationId) {
            console.warn('Không có conversationId, sử dụng polling fallback');
            this.startAdminPolling();
            return;
        }

        try {
            // Disconnect existing channel if any
            this.disconnectAdminWebSocket();
            
            // Subscribe to conversation channel
            this.adminChannel = window.Echo.private(`chat.conversation.${this.conversationId}`);

            // Listen for new messages
            this.adminChannel.listen('.message.new', (data) => {
                this.handleWebSocketMessage(data.message);
            });

            // Listen for typing status
            this.adminChannel.listen('.user.typing', (data) => {
                this.handleAdminTyping(data);
            });

            this.adminChannel.subscribed(() => {
                console.log('WebSocket: Đã kết nối channel', this.conversationId);
                this.isWebSocketConnected = true;
                this.stopAdminPolling(); // Stop polling when WebSocket connected
            });

            this.adminChannel.error((error) => {
                console.error('WebSocket error:', error);
                this.isWebSocketConnected = false;
                this.startAdminPolling(); // Fallback to polling
            });

        } catch (err) {
            console.error('WebSocket connection error:', err);
            this.startAdminPolling();
        }
    }

    disconnectAdminWebSocket() {
        if (this.adminChannel && window.Echo) {
            window.Echo.leave(`chat.conversation.${this.conversationId}`);
            this.adminChannel = null;
        }
        this.isWebSocketConnected = false;
        this.stopAdminPolling();
    }

    handleWebSocketMessage(message) {
        // Bỏ qua tin nhắn từ chính user (đã hiển thị qua optimistic UI)
        const senderType = message.sender_type || message.LoaiNguoiGui;
        if (senderType === 'NguoiDung') {
            // Tin nhắn từ user đã được hiển thị qua optimistic UI
            return;
        }

        // Check if message already exists
        const exists = this.adminMessages.some(m => 
            m.ID === message.id || m.ID === message.ID
        );
        if (exists) return;

        // Convert WebSocket format to local format
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

        this.adminMessages.push(formattedMessage);
        this.renderAdminMessages();

        // Hide typing indicator
        this.hideAdminTyping();

        // Play notification sound if message from admin
        if (formattedMessage.LoaiNguoiGui === 'Admin') {
            this.playNotificationSound();
        }
    }

    handleAdminTyping(data) {
        if (data.userType === 'Admin' || data.user_type === 'Admin') {
            if (data.isTyping || data.is_typing) {
                this.showAdminTyping(data.userName || data.user_name || 'Admin');
            } else {
                this.hideAdminTyping();
            }
        }
    }

    showAdminTyping(name = 'Admin') {
        if (this.isAdminTyping) return;
        this.isAdminTyping = true;

        // Remove existing indicator
        this.hideAdminTyping();

        const typingDiv = document.createElement('div');
        typingDiv.className = 'chatbot-message bot';
        typingDiv.id = 'admin-typing-indicator';
        typingDiv.innerHTML = `
            <img src="template/Assets/Images/admin-avatar.png" alt="Admin" class="chatbot-message-avatar">
            <div class="chatbot-message-content">
                <div class="chatbot-message-bubble">
                    <div class="chatbot-typing">
                        <div class="chatbot-typing-dot"></div>
                        <div class="chatbot-typing-dot"></div>
                        <div class="chatbot-typing-dot"></div>
                    </div>
                </div>
                <div class="chatbot-typing-text">${this.escapeHtml(name)} đang gõ...</div>
            </div>
        `;

        this.body.appendChild(typingDiv);
        this.scrollToBottom();
    }

    hideAdminTyping() {
        this.isAdminTyping = false;
        const indicator = document.getElementById('admin-typing-indicator');
        if (indicator) {
            indicator.remove();
        }
    }

    playNotificationSound() {
        try {
            const audio = new Audio('/template/Assets/sounds/notification.mp3');
            audio.volume = 0.5;
            audio.play().catch(() => {});
        } catch (e) {
            // Ignore audio errors
        }
    }

    updateChatTitle() {
        if (this.chatTitle) {
            if (this.chatMode === "admin") {
                this.chatTitle.innerHTML =
                    '<i class="fa-solid fa-headset"></i> Chat với Admin';
            } else {
                this.chatTitle.innerHTML =
                    '<i class="fa-solid fa-robot"></i> Trợ lý ảo';
            }
        }

        // Update container class
        if (this.container) {
            this.container.classList.toggle(
                "admin-mode",
                this.chatMode === "admin"
            );
        }
    }

    async loadAdminConversation() {
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
                this.adminInfo = data.conversation.admin || this.adminInfo;
                await this.loadAdminMessages();
            }
        } catch (error) {
            console.error("Error loading conversation:", error);
            this.addAdminSystemMessage(
                "Không thể kết nối với hệ thống chat. Vui lòng thử lại sau."
            );
        }
    }

    async loadAdminMessages() {
        if (!this.conversationId) return;

        try {
            const response = await fetch(
                `${this.config.chatApiUrl}/messages/${this.conversationId}`,
                {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                    },
                    credentials: "same-origin",
                }
            );

            if (response.status === 404) {
                // Conversation missing for this session (likely stale sessionStorage)
                await this.resetConversationAndReload();
                return;
            }

            const data = await response.json();

            if (data.success) {
                this.adminMessages = data.messages || [];
                this.adminInfo = data.admin || this.adminInfo;

                // If no messages yet, add welcome message
                if (this.adminMessages.length === 0) {
                    this.addAdminSystemMessage(
                        "Chào bạn! Hãy để lại tin nhắn, Admin sẽ phản hồi sớm nhất có thể."
                    );
                } else {
                    this.renderAdminMessages();
                }
            } else {
                this.addAdminSystemMessage(
                    "Không thể tải cuộc hội thoại. Đang thử khởi tạo lại..."
                );
                await this.resetConversationAndReload();
            }
        } catch (error) {
            console.error("Error loading messages:", error);
        }
    }

    addAdminSystemMessage(text) {
        const msg = {
            ID: Date.now(),
            LoaiNguoiGui: "HeThong",
            NoiDung: text,
            ThoiGianGui: new Date().toISOString(),
        };
        this.adminMessages.push(msg);
        this.renderAdminMessages();
    }

    renderAdminMessages() {
        if (!this.body) return;

        this.body.innerHTML = "";

        this.adminMessages.forEach((msg) => {
            const messageDiv = document.createElement("div");

            if (msg.LoaiNguoiGui === "HeThong") {
                messageDiv.className = "chatbot-message system";
                messageDiv.innerHTML = `
                    <div class="chatbot-system-message">
                        ${this.escapeHtml(msg.NoiDung)}
                    </div>
                `;
            } else {
                const isUser = msg.LoaiNguoiGui === "NguoiDung";
                messageDiv.className = `chatbot-message ${
                    isUser ? "user" : "bot"
                }`;

                const sender = msg.nguoi_gui || {};
                const avatarUrl = isUser
                    ? sender.HinhAnh ||
                      "template/Assets/Images/default-avatar.png"
                    : sender.HinhAnh ||
                      this.adminInfo?.HinhAnh ||
                      "template/Assets/Images/admin-avatar.png";

                const senderName = isUser
                    ? ""
                    : sender.TenNguoiDung ||
                      this.adminInfo?.TenNguoiDung ||
                      "Admin";

                let html = "";

                if (!isUser) {
                    html += `<img src="${avatarUrl}" alt="Admin" class="chatbot-message-avatar" onerror="this.src='template/Assets/Images/admin-avatar.png'">`;
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

                messageDiv.innerHTML = html;
            }

            this.body.appendChild(messageDiv);
        });

        this.scrollToBottom();
    }

    async sendAdminMessage(text) {
        if (!this.conversationId || !text.trim()) return;

        this.isSending = true;

        // Optimistically add message
        const tempMsg = {
            ID: "temp-" + Date.now(),
            LoaiNguoiGui: "NguoiDung",
            NoiDung: text,
            ThoiGianGui: new Date().toISOString(),
        };
        this.adminMessages.push(tempMsg);
        this.renderAdminMessages();

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
                // Conversation lost (new session); recreate and retry once
                await this.resetConversationAndReload();
                this.isSending = false;
                await this.sendAdminMessage(text);
                return;
            }

            const data = await response.json();

            if (data.success) {
                // Replace temp message with real one
                const tempIndex = this.adminMessages.findIndex(
                    (m) => m.ID === tempMsg.ID
                );
                if (tempIndex !== -1 && data.message) {
                    this.adminMessages[tempIndex] = data.message;
                    this.renderAdminMessages();
                }
            } else {
                // Remove temp message on error
                this.adminMessages = this.adminMessages.filter(
                    (m) => m.ID !== tempMsg.ID
                );
                this.renderAdminMessages();
                this.addAdminSystemMessage(
                    "Không thể gửi tin nhắn. Vui lòng thử lại."
                );
            }
        } catch (error) {
            console.error("Error sending message:", error);
            this.adminMessages = this.adminMessages.filter(
                (m) => m.ID !== tempMsg.ID
            );
            this.renderAdminMessages();
            this.addAdminSystemMessage("Lỗi kết nối. Vui lòng thử lại.");
        } finally {
            this.isSending = false;
        }
    }

    async resetConversationAndReload() {
        this.conversationId = null;
        this.adminMessages = [];
        this.adminInfo = null;
        this.saveState();
        await this.loadAdminConversation();
    }

    startAdminPolling() {
        // Only start polling if WebSocket is not connected
        if (this.isWebSocketConnected) return;
        
        this.stopAdminPolling();

        console.log('Bắt đầu polling fallback cho admin chat');
        this.adminRefreshInterval = setInterval(() => {
            if (this.chatMode === "admin" && this.conversationId && !this.isWebSocketConnected) {
                this.loadAdminMessages();
            }
        }, 3000); // Poll every 3 seconds (faster fallback)
    }

    stopAdminPolling() {
        if (this.adminRefreshInterval) {
            clearInterval(this.adminRefreshInterval);
            this.adminRefreshInterval = null;
        }
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

    showInitialGreeting() {
        // Add greeting message with image
        const greetingMessage = {
            type: "bot",
            text: this.config.greeting,
            image: null,
            timestamp: new Date(),
            quickReplies: null,
        };

        this.addMessage(greetingMessage);

        // Add a sample message with image and quick replies after a short delay
        setTimeout(() => {
            const sampleMessage = {
                type: "bot",
                text: "Chào bạn! Chúng tôi có nhiều sản phẩm nông sản tươi ngon. Bạn quan tâm đến loại sản phẩm nào?",
                image: null,
                timestamp: new Date(),
                quickReplies: [
                    { text: "Rau củ quả", value: "vegetables" },
                    { text: "Trái cây", value: "fruits" },
                    { text: "Thịt & Hải sản", value: "meat" },
                ],
            };
            this.addMessage(sampleMessage);
        }, 1000);
    }

    async handleSend() {
        const text = this.input.value.trim();

        if (!text || this.isSending) return;

        // Clear input first
        this.input.value = "";

        // Route to appropriate handler based on mode
        if (this.chatMode === "admin") {
            await this.sendAdminMessage(text);
        } else {
            // Add user message
            const userMessage = {
                type: "user",
                text: text,
                timestamp: new Date(),
            };

            this.addMessage(userMessage);
            await this.queryBot(text);
        }
    }

    async queryBot(userText) {
        this.isSending = true;
        this.showTyping();

        const payload = {
            message: userText,
            history: this.buildHistory(),
        };

        try {
            const res = await fetch(this.config.apiUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok || data.error) {
                throw new Error(data.error || "Chat service error");
            }

            const reply =
                data.reply ||
                "Mình chưa lấy được câu trả lời, bạn thử lại giúp mình nhé.";
            const botMessage = {
                type: "bot",
                text: reply,
                timestamp: new Date(),
            };

            this.addMessage(botMessage);
        } catch (err) {
            console.error("Chatbot error:", err);
            this.addMessage({
                type: "bot",
                text: "Xin lỗi, hệ thống đang bận. Bạn vui lòng thử lại sau.",
                timestamp: new Date(),
            });
        } finally {
            this.hideTyping();
            this.isSending = false;
        }
    }

    toggleMenu() {
        if (!this.menu) return;
        const willOpen = !this.menu.classList.contains("open");
        if (willOpen) {
            this.menu.classList.add("open");
        } else {
            this.menu.classList.remove("open");
        }
    }

    closeMenu() {
        if (this.menu) {
            this.menu.classList.remove("open");
        }
    }

    buildHistory() {
        // Keep a short history for context while limiting payload size
        return this.messages
            .filter((m) => m.type === "user" || m.type === "bot")
            .slice(-10)
            .map((m) => ({
                role: m.type === "bot" ? "assistant" : "user",
                content: m.text || "",
            }));
    }

    addMessage(message) {
        this.messages.push(message);
        this.renderMessage(message);
        this.scrollToBottom();
        this.saveState();
    }

    renderMessages() {
        this.body.innerHTML = "";
        this.messages.forEach((message) => this.renderMessage(message));
        this.scrollToBottom();
    }

    renderMessage(message) {
        const messageDiv = document.createElement("div");
        messageDiv.className = `chatbot-message ${message.type}`;

        let html = "";

        if (message.type === "bot") {
            html += `
                <img src="template/Assets/Images/chatbot/chatbot3.jpg" alt="Bot" class="chatbot-message-avatar" 
                     onerror="this.style.display='none'">
            `;
        }

        html += '<div class="chatbot-message-content">';

        // Add image if present
        if (message.image) {
            html += `
                <div class="chatbot-message-image">
                    <img src="${message.image}" alt="Message image">
                </div>
            `;
        }

        // Add text bubble
        html += `
            <div class="chatbot-message-bubble">
                ${this.escapeHtml(message.text)}
            </div>
        `;

        // Add quick replies if present
        if (message.quickReplies && message.quickReplies.length > 0) {
            html += '<div class="chatbot-quick-replies">';
            message.quickReplies.forEach((reply) => {
                html += `
                    <button class="chatbot-quick-reply-btn" data-value="${
                        reply.value
                    }">
                        ${this.escapeHtml(reply.text)}
                    </button>
                `;
            });
            html += "</div>";
        }

        html += "</div>";

        messageDiv.innerHTML = html;

        // Bind quick reply buttons
        if (message.quickReplies) {
            const buttons = messageDiv.querySelectorAll(
                ".chatbot-quick-reply-btn"
            );
            buttons.forEach((btn) => {
                btn.addEventListener("click", (e) => {
                    const text = e.target.textContent.trim();
                    this.handleQuickReply(text);
                });
            });
        }

        this.body.appendChild(messageDiv);
    }

    async handleQuickReply(text) {
        // Add as user message
        const userMessage = {
            type: "user",
            text: text,
            timestamp: new Date(),
        };

        this.addMessage(userMessage);

        if (this.isSending) return;
        await this.queryBot(text);
    }

    showTyping() {
        const typingDiv = document.createElement("div");
        typingDiv.className = "chatbot-message bot";
        typingDiv.id = "chatbotTyping";
        typingDiv.innerHTML = `
            <img src="${this.config.avatarUrl}" alt="Bot" class="chatbot-message-avatar" 
                 onerror="this.style.display='none'">
            <div class="chatbot-message-content">
                <div class="chatbot-message-bubble">
                    <div class="chatbot-typing">
                        <div class="chatbot-typing-dot"></div>
                        <div class="chatbot-typing-dot"></div>
                        <div class="chatbot-typing-dot"></div>
                    </div>
                </div>
            </div>
        `;
        this.body.appendChild(typingDiv);
        this.scrollToBottom();
    }

    hideTyping() {
        const typingDiv = document.getElementById("chatbotTyping");
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    scrollToBottom() {
        setTimeout(() => {
            this.body.scrollTop = this.body.scrollHeight;
        }, 100);
    }

    saveState() {
        try {
            const state = {
                isOpen: this.isOpen,
                messages: this.messages,
                chatMode: this.chatMode,
                conversationId: this.conversationId,
                adminMessages: this.adminMessages,
                adminInfo: this.adminInfo,
            };
            sessionStorage.setItem("chatbotState", JSON.stringify(state));
        } catch (e) {
            console.warn("Could not save chatbot state:", e);
        }
    }

    loadState() {
        try {
            const stateStr = sessionStorage.getItem("chatbotState");
            if (stateStr) {
                const state = JSON.parse(stateStr);
                this.isOpen = state.isOpen || false;
                this.messages = state.messages || [];
                this.chatMode = state.chatMode || "bot";
                this.conversationId = state.conversationId || null;
                this.adminMessages = state.adminMessages || [];
                this.adminInfo = state.adminInfo || null;

                if (this.isOpen) {
                    this.container.classList.add("active");
                    if (this.icon) this.icon.style.display = "block";
                    if (this.iconImg) this.iconImg.style.display = "none";
                    
                    // Connect WebSocket if in admin mode and chat is open
                    if (this.chatMode === "admin" && this.conversationId) {
                        // Delay WebSocket connection to ensure Echo is ready
                        setTimeout(() => {
                            this.connectAdminWebSocket();
                        }, 1000);
                    }
                }

                // Update title based on mode
                this.updateChatTitle();

                this.updateAuxButtons();
            }
        } catch (e) {
            console.warn("Could not load chatbot state:", e);
        }
    }

    openLauncher() {
        if (this.launcher) {
            this.launcher.style.display = "flex";
            document.body.classList.add("chatbot-blur");
        }
    }

    closeLauncher() {
        if (this.launcher) {
            this.launcher.style.display = "none";
            document.body.classList.remove("chatbot-blur");
        }
    }

    openContactForm() {
        if (!this.contactModal) return;
        this.prefillContactTitle();
        this.contactModal.style.display = "flex";
        document.body.classList.add("chatbot-blur");
    }

    closeContactForm() {
        if (!this.contactModal) return;
        this.contactModal.style.display = "none";
        document.body.classList.remove("chatbot-blur");
    }

    prefillContactTitle() {
        if (!this.contactTitleInput) return;
        const product = (this.contactProductInput?.value || "").trim();
        this.contactTitleInput.value = product
            ? `Tư vấn về ${product}`
            : "Yêu cầu tư vấn từ chatbox";
    }

    async submitContactForm(event) {
        event.preventDefault();
        if (!this.contactForm) return;

        this.prefillContactTitle();

        if (this.contactSubmitBtn) {
            this.contactSubmitBtn.disabled = true;
            this.contactSubmitBtn.classList.add("is-loading");
        }

        const headers = {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        };

        if (this.csrfToken) {
            headers["X-CSRF-TOKEN"] = this.csrfToken;
        }

        try {
            const response = await fetch(this.contactForm.action, {
                method: "POST",
                credentials: "same-origin",
                headers,
                body: new FormData(this.contactForm),
            });

            if (response.status === 422) {
                const data = await response.json();
                const errors = Object.values(data.errors || {})
                    .flat()
                    .join(", ");
                this.notify(
                    errors || "Vui lòng kiểm tra lại thông tin.",
                    "error"
                );
                return;
            }

            if (!response.ok) {
                throw new Error("network");
            }

            const data = await response.json().catch(() => ({}));
            this.notify(
                data?.message ||
                    "Đã gửi yêu cầu liên hệ. Chúng tôi sẽ phản hồi sớm.",
                "success"
            );
            this.contactForm.reset();
            if (this.contactTitleInput) {
                this.contactTitleInput.value = "Yêu cầu tư vấn từ chatbox";
            }
            this.closeContactForm();
        } catch (error) {
            this.notify(
                "Không thể gửi yêu cầu, vui lòng thử lại sau.",
                "error"
            );
        } finally {
            if (this.contactSubmitBtn) {
                this.contactSubmitBtn.disabled = false;
                this.contactSubmitBtn.classList.remove("is-loading");
            }
        }
    }

    isLauncherOpen() {
        return (
            this.launcher &&
            (this.launcher.style.display === "flex" ||
                this.launcher.style.display === "block")
        );
    }

    updateAuxButtons() {
        const scrollBtn = document.getElementById("scrollToTopBtn");
        if (scrollBtn) {
            scrollBtn.style.display = this.isOpen ? "none" : "";
        }

        if (this.toggleBtn) {
            this.toggleBtn.style.display = this.isOpen ? "none" : "";
        }
    }

    escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute("content") : "";
    }

    notify(message, type = "info") {
        if (!message) return;
        if (window.AppAlert && typeof window.AppAlert.show === "function") {
            window.AppAlert.show(message, { type });
        } else {
            alert(message);
        }
    }

    contactAdmin() {
        // Switch to admin chat mode
        this.switchToAdminMode();
    }

    clearChat() {
        if (this.chatMode === "admin") {
            // Clear admin messages but keep conversation
            this.adminMessages = [];
            this.renderAdminMessages();
            this.addAdminSystemMessage(
                "Đoạn chat đã được xóa. Hãy gửi tin nhắn mới để tiếp tục."
            );
        } else {
            // Clear bot messages
            this.messages = [];

            // Clear chat body UI
            if (this.body) {
                this.body.innerHTML = "";
            }

            // Show initial greeting again
            this.showInitialGreeting();
        }

        // Clear saved state
        try {
            sessionStorage.removeItem("chatbotState");
        } catch (e) {
            console.warn("Could not clear chatbot state:", e);
        }
    }
}

// Scroll to Top functionality
function initScrollToTop() {
    const scrollBtn = document.getElementById("scrollToTopBtn");

    if (!scrollBtn) return;

    // Show/hide button based on scroll position
    window.addEventListener("scroll", function () {
        if (window.pageYOffset > 300) {
            scrollBtn.classList.add("show");
        } else {
            scrollBtn.classList.remove("show");
        }
    });

    // Scroll to top when clicked
    scrollBtn.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    });
}

// Initialize chatbot when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.chatbotConfig !== "undefined") {
        window.chatbot = new ChatbotWidget(window.chatbotConfig);
    }

    // Initialize scroll to top button
    initScrollToTop();
});
