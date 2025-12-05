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
        };

        this.isOpen = false;
        this.messages = [];
        this.isSending = false;

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
        this.launcherCloseBtn = document.getElementById("chatLauncherClose");

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
                if (!this.isOpen) this.toggleWidget();
            });
        }

        if (this.launchAdminBtn) {
            this.launchAdminBtn.addEventListener("click", () => {
                this.closeLauncher();
                if (!this.isOpen) this.toggleWidget();
                this.contactAdmin();
            });
        }

        if (this.launchZaloBtn) {
            this.launchZaloBtn.addEventListener("click", () => {
                this.closeLauncher();
                window.open("https://zalo.me/", "_blank", "noopener");
            });
        }

        if (this.launcherBackdrop) {
            this.launcherBackdrop.addEventListener("click", () =>
                this.closeLauncher()
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
        } else {
            this.container.classList.remove("active");
            if (this.icon) this.icon.style.display = "none";
            if (this.iconImg) this.iconImg.style.display = "block";
            this.closeMenu();
            this.closeLauncher();
        }

        this.updateAuxButtons();
        this.saveState();
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

        // Add user message
        const userMessage = {
            type: "user",
            text: text,
            timestamp: new Date(),
        };

        this.addMessage(userMessage);

        // Clear input
        this.input.value = "";

        await this.queryBot(text);
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

                if (this.isOpen) {
                    this.container.classList.add("active");
                    if (this.icon) this.icon.style.display = "block";
                    if (this.iconImg) this.iconImg.style.display = "none";
                }

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

    contactAdmin() {
        // Add system message
        const adminMessage = {
            type: "bot",
            text: "Đang kết nối bạn với Admin... Vui lòng để lại số điện thoại hoặc email để Admin liên hệ lại với bạn.",
            timestamp: new Date(),
        };
        this.addMessage(adminMessage);

        // You can add more logic here like opening a contact form or redirecting
        console.log("Contact admin requested");
    }

    clearChat() {
        // Clear messages array
        this.messages = [];

        // Clear chat body UI
        if (this.body) {
            this.body.innerHTML = "";
        }

        // Clear saved state
        try {
            localStorage.removeItem("chatbotMessages");
        } catch (e) {
            console.warn("Could not clear chatbot state:", e);
        }

        // Show initial greeting again
        this.showInitialGreeting();
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
