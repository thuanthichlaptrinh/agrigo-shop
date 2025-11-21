/**
 * Chatbot Widget
 * A reusable chat widget for customer support
 */

class ChatbotWidget {
    constructor(config) {
        this.config = {
            botName: config.botName || 'Support Bot',
            avatarUrl: 'template/Assets/Images/chatbot/chatbot3.jpg' || 'template/Assets/Images/chatbot/chatbot2.jpg',
            greeting: config.greeting || 'Hello! How can I help you?'
        };
        
        this.isOpen = false;
        this.messages = [];
        
        // DOM Elements
        this.widget = document.getElementById('chatbotWidget');
        this.toggleBtn = document.getElementById('chatbotToggle');
        this.container = document.getElementById('chatbotContainer');
        this.body = document.getElementById('chatbotBody');
        this.input = document.getElementById('chatbotInput');
        this.sendBtn = document.getElementById('chatbotSend');
        this.minimizeBtn = document.getElementById('chatbotMinimize');
        this.icon = document.getElementById('chatbotIcon');
        
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
    }
    
    bindEvents() {
        // Toggle button
        this.toggleBtn.addEventListener('click', () => this.toggleWidget());
        
        // Minimize button
        this.minimizeBtn.addEventListener('click', () => this.toggleWidget());
        
        // Contact Admin button
        const contactAdminBtn = document.getElementById('chatbotContactAdmin');
        if (contactAdminBtn) {
            contactAdminBtn.addEventListener('click', () => this.contactAdmin());
        }
        
        // Send button
        this.sendBtn.addEventListener('click', () => this.handleSend());
        
        // Enter key in input
        this.input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.handleSend();
            }
        });
        
        // Emoji button (placeholder functionality)
        const emojiBtn = document.querySelector('.chatbot-emoji-btn');
        if (emojiBtn) {
            emojiBtn.addEventListener('click', () => {
                // Placeholder for emoji picker
                console.log('Emoji picker would open here');
            });
        }
    }
    
    toggleWidget() {
        this.isOpen = !this.isOpen;
        
        if (this.isOpen) {
            this.container.classList.add('active');
            this.icon.className = 'ri-close-line';
            this.input.focus();
        } else {
            this.container.classList.remove('active');
            this.icon.className = 'ri-message-3-line';
        }
        
        this.saveState();
    }
    
    showInitialGreeting() {
        // Add greeting message with image
        const greetingMessage = {
            type: 'bot',
            text: this.config.greeting,
            image: null,
            timestamp: new Date(),
            quickReplies: null
        };
        
        this.addMessage(greetingMessage);
        
        // Add a sample message with image and quick replies after a short delay
        setTimeout(() => {
            const sampleMessage = {
                type: 'bot',
                text: 'Chào bạn! Chúng tôi có nhiều sản phẩm nông sản tươi ngon. Bạn quan tâm đến loại sản phẩm nào?',
                image: null,
                timestamp: new Date(),
                quickReplies: [
                    { text: 'Rau củ quả', value: 'vegetables' },
                    { text: 'Trái cây', value: 'fruits' },
                    { text: 'Thịt & Hải sản', value: 'meat' }
                ]
            };
            this.addMessage(sampleMessage);
        }, 1000);
    }
    
    handleSend() {
        const text = this.input.value.trim();
        
        if (!text) return;
        
        // Add user message
        const userMessage = {
            type: 'user',
            text: text,
            timestamp: new Date()
        };
        
        this.addMessage(userMessage);
        
        // Clear input
        this.input.value = '';
        
        // Simulate bot response
        this.simulateBotResponse(text);
    }
    
    simulateBotResponse(userText) {
        // Show typing indicator
        this.showTyping();
        
        // Simulate delay
        setTimeout(() => {
            this.hideTyping();
            
            // Generate response based on user input
            let responseText = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ hỗ trợ bạn ngay.';
            let quickReplies = null;
            
            const lowerText = userText.toLowerCase();
            
            if (lowerText.includes('rau') || lowerText.includes('vegetables')) {
                responseText = 'Chúng tôi có nhiều loại rau củ tươi ngon: rau xanh, củ cải, cà rốt... Bạn muốn xem sản phẩm nào?';
                quickReplies = [
                    { text: 'Rau xanh', value: 'green_veg' },
                    { text: 'Củ quả', value: 'root_veg' },
                    { text: 'Xem tất cả', value: 'all_veg' }
                ];
            } else if (lowerText.includes('trái cây') || lowerText.includes('fruits')) {
                responseText = 'Trái cây tươi ngon của chúng tôi: táo, cam, nho, dưa hấu... Bạn thích loại nào?';
                quickReplies = [
                    { text: 'Trái cây nhập khẩu', value: 'imported' },
                    { text: 'Trái cây Việt Nam', value: 'local' },
                    { text: 'Xem tất cả', value: 'all_fruits' }
                ];
            } else if (lowerText.includes('thịt') || lowerText.includes('meat') || lowerText.includes('hải sản')) {
                responseText = 'Chúng tôi cung cấp thịt tươi và hải sản chất lượng cao. Bạn cần loại nào?';
                quickReplies = [
                    { text: 'Thịt heo', value: 'pork' },
                    { text: 'Thịt gà', value: 'chicken' },
                    { text: 'Hải sản', value: 'seafood' }
                ];
            } else if (lowerText.includes('giá') || lowerText.includes('price')) {
                responseText = 'Giá sản phẩm của chúng tôi rất cạnh tranh. Bạn muốn biết giá sản phẩm nào?';
            } else if (lowerText.includes('giao hàng') || lowerText.includes('delivery')) {
                responseText = 'Chúng tôi giao hàng miễn phí cho đơn hàng từ 200.000đ trong nội thành TP.HCM. Bạn cần thêm thông tin gì?';
            }
            
            const botMessage = {
                type: 'bot',
                text: responseText,
                timestamp: new Date(),
                quickReplies: quickReplies
            };
            
            this.addMessage(botMessage);
        }, 1500);
    }
    
    addMessage(message) {
        this.messages.push(message);
        this.renderMessage(message);
        this.scrollToBottom();
        this.saveState();
    }
    
    renderMessages() {
        this.body.innerHTML = '';
        this.messages.forEach(message => this.renderMessage(message));
        this.scrollToBottom();
    }
    
    renderMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${message.type}`;
        
        let html = '';
        
        if (message.type === 'bot') {
            html += `
                <img src="${this.config.avatarUrl}" alt="Bot" class="chatbot-message-avatar" 
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
            message.quickReplies.forEach(reply => {
                html += `
                    <button class="chatbot-quick-reply-btn" data-value="${reply.value}">
                        ${this.escapeHtml(reply.text)}
                    </button>
                `;
            });
            html += '</div>';
        }
        
        html += '</div>';
        
        messageDiv.innerHTML = html;
        
        // Bind quick reply buttons
        if (message.quickReplies) {
            const buttons = messageDiv.querySelectorAll('.chatbot-quick-reply-btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const text = e.target.textContent.trim();
                    this.handleQuickReply(text);
                });
            });
        }
        
        this.body.appendChild(messageDiv);
    }
    
    handleQuickReply(text) {
        // Add as user message
        const userMessage = {
            type: 'user',
            text: text,
            timestamp: new Date()
        };
        
        this.addMessage(userMessage);
        
        // Simulate bot response
        this.simulateBotResponse(text);
    }
    
    showTyping() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chatbot-message bot';
        typingDiv.id = 'chatbotTyping';
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
        const typingDiv = document.getElementById('chatbotTyping');
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
                messages: this.messages
            };
            sessionStorage.setItem('chatbotState', JSON.stringify(state));
        } catch (e) {
            console.warn('Could not save chatbot state:', e);
        }
    } 
    
    loadState() {
        try {
            const stateStr = sessionStorage.getItem('chatbotState');
            if (stateStr) {
                const state = JSON.parse(stateStr);
                this.isOpen = state.isOpen || false;
                this.messages = state.messages || [];
                
                if (this.isOpen) {
                    this.container.classList.add('active');
                    this.icon.className = 'ri-close-line';
                }
            }
        } catch (e) {
            console.warn('Could not load chatbot state:', e);
        }
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    contactAdmin() {
        // Add system message
        const adminMessage = {
            type: 'bot',
            text: 'Đang kết nối bạn với Admin... Vui lòng để lại số điện thoại hoặc email để Admin liên hệ lại với bạn.',
            timestamp: new Date()
        };
        this.addMessage(adminMessage);
        
        // You can add more logic here like opening a contact form or redirecting
        console.log('Contact admin requested');
    }
}

// Scroll to Top functionality
function initScrollToTop() {
    const scrollBtn = document.getElementById('scrollToTopBtn');
    
    if (!scrollBtn) return;
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });
    
    // Scroll to top when clicked
    scrollBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.chatbotConfig !== 'undefined') {
        window.chatbot = new ChatbotWidget(window.chatbotConfig);
    }
    
    // Initialize scroll to top button
    initScrollToTop();
});
