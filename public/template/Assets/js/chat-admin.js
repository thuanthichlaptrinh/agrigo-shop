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
        }
    }

    toggle() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            this.container?.classList.add("active");
            if (this.icon) this.icon.style.display = "block";
            if (this.iconImg) this.iconImg.style.display = "none";
            this.input?.focus();
        } else {
            this.container?.classList.remove("active");
            if (this.icon) this.icon.style.display = "none";
            if (this.iconImg) this.iconImg.style.display = "block";
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
