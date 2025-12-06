{{-- Chatbot Widget Component --}}
@php
    $botName = $botName ?? 'Organic Shop Support';
    $avatarUrl = $avatarUrl ?? asset('template/Assets/Images/bot-avatar.png');
    $greeting = $greeting ?? 'Xin chào! Tôi có thể giúp gì cho bạn?';
@endphp

<div class="chatbot-widget" id="chatbotWidget">
    <!-- Toggle Button -->
    <button class="chatbot-toggle-btn" id="chatbotToggle" aria-label="Toggle chat">
        <img src="{{ asset('template/Assets/Images/chatbot/chatbox.png') }}" alt="Chatbot" id="chatbotIconImg" class="chatbot-toggle-img">
        <i class="ri-close-line" id="chatbotIcon" aria-hidden="true" style="display: none;"></i>
    </button>

    <!-- Launcher Options Overlay -->
    <div class="chatbot-launcher" id="chatbotLauncher" aria-label="Chọn kênh chat" style="display: none;">
        <div class="chatbot-launcher-backdrop" id="chatbotLauncherBackdrop"></div>
        <div class="chatbot-launcher-panel">
            <h5 class="chatbot-launcher-title">Bạn muốn trò chuyện qua kênh nào?</h5>
            <div class="chatbot-launcher-actions">
                <button class="chatbot-launcher-btn" id="chatLaunchAssistant">
                    <i class="ri-robot-2-line"></i>
                    <span>Trợ lý ảo</span>
                </button>
                <button class="chatbot-launcher-btn" id="chatLaunchAdmin">
                    <i class="ri-customer-service-2-line"></i>
                    <span>Chat với Admin</span>
                </button>
                <button class="chatbot-launcher-btn" id="chatLaunchZalo">
                    <i class="ri-message-2-line"></i>
                    <span>Tư vấn qua Zalo</span>
                </button>
                <button class="chatbot-launcher-btn" id="chatLaunchContact">
                    <i class="ri-mail-send-line"></i>
                    <span>Liên hệ</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="chatbot-container" id="chatbotContainer">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="chatbot-header-content">
                <div class="chatbot-header-left">
                    <!-- <img src="{{ $avatarUrl }}" alt="Bot Avatar" class="chatbot-avatar" onerror="this.src='{{ asset('template/Assets/Images/logo2.png') }}'"> -->
                    <img src="{{ asset('template/Assets/Images/chatbot/chatbot3.jpg') }}"  alt="Bot Avatar" class="chatbot-avatar" onerror="this.src='{{ asset('template/Assets/Images/logo2.png') }}'">
                    <div class="chatbot-header-info">
                        <h3 class="chatbot-title" id="chatbotTitle"><i class="fa-solid fa-robot"></i> Trợ lý ảo</h3>
                        <div class="chatbot-status">
                            <span class="chatbot-status-dot"></span>
                            <span>Chúng tôi đang online!</span>
                        </div>
                    </div>
                </div>
                <div class="chatbot-header-actions">
                    <button class="chatbot-header-btn" id="chatbotMinimize" aria-label="Minimize chat">
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <button class="chatbot-header-btn" id="chatbotMenuToggle" aria-label="Menu">
                        <i class="ri-more-fill"></i>
                    </button>
                    <div class="chatbot-menu" id="chatbotMenu" aria-label="Chatbot menu">
                        <button class="chatbot-menu-item" data-action="clear-chat">
                            <i class="ri-delete-bin-line"></i>
                            <span>Xóa đoạn chat</span>
                        </button>
                    </div>
                </div>
            </div>
            <svg class="chatbot-wave" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0 C150,80 350,80 600,50 C850,20 1050,20 1200,50 L1200,120 L0,120 Z" fill="#fafafa"></path>
            </svg>
        </div>

        <!-- Body (Messages Area) -->
        <div class="chatbot-body" id="chatbotBody" role="log" aria-live="polite" aria-atomic="false">
            <!-- Messages will be dynamically added here -->
        </div>

        <!-- Contact Admin Button -->
        <div class="chatbot-contact-admin" style="display: none;">
            <button class="chatbot-admin-btn" id="chatbotContactAdmin">
                <i class="ri-customer-service-2-line"></i>
                <span>Chat với Admin</span>
            </button>
        </div>

        <!-- Input Area -->
        <div class="chatbot-input-area">
            <div class="chatbot-input-wrapper">
                <button class="chatbot-emoji-btn" aria-label="Add emoji">
                    <i class="ri-emotion-line"></i>
                </button>
                <input 
                    type="text" 
                    class="chatbot-input" 
                    id="chatbotInput" 
                    placeholder="Enter your message..."
                    aria-label="Type your message"
                    autocomplete="off"
                >
            </div>
            <button class="chatbot-send-btn" id="chatbotSend" aria-label="Send message">
                <i class="ri-send-plane-fill"></i>
            </button>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top-btn" id="scrollToTopBtn" aria-label="Scroll to top">
        <i class="ri-arrow-up-line"></i>
    </button>
</div>

<!-- Contact Modal for Launcher -->
<div class="chat-contact-modal" id="chatContactModal" style="display:none;">
    <div class="chat-contact-overlay" id="chatContactOverlay"></div>
    
    <div class="chat-contact-wrapper">
        <button type="button" class="chat-contact-close" id="chatContactClose" aria-label="Đóng">
            <i class="ri-close-line"></i>
        </button>

        <div class="chat-contact-header-content">
            <div class="chat-contact-logo-circle">
                <img src="{{ asset('template/Assets/Images/logo5.png') }}" alt="Logo" onerror="this.style.display='none'">
            </div>
            <h2 class="chat-contact-title">Agrigo Shop</h2>
            <p class="chat-contact-desc">Chào mừng Quý Khách đến với Organic Shop. Vui lòng nhập thông tin để được chuyên viên tư vấn hỗ trợ.</p>
        </div>

        <div class="chat-contact-form-card">
            <form id="chatContactForm" action="{{ route('user.contact.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="form_type" value="launcher-contact">
                <input type="hidden" name="HoTen" value="Khách chat">
                <input type="hidden" name="Email" value="widget@organicshop.local">
                <input type="hidden" name="TieuDe" id="chatContactTitle" value="Yêu cầu tư vấn từ chatbox">

                <div class="chat-contact-scroll-area">
                    <div class="form-group">
                        <label>Số điện thoại<span class="required">*</span></label>
                        <input type="tel" name="SDT" placeholder="Nhập số điện thoại" required>
                    </div>

                    <div class="form-group">
                        <label>Sản phẩm <span class="required">*</span></label>
                        <input type="text" name="Product" id="chatContactProduct" placeholder="Sản phẩm Quý Khách cần tư vấn">
                    </div>

                    <div class="form-group">
                        <label>Tin nhắn<span class="required">*</span></label>
                        <textarea name="NoiDung" id="chatContactMessage" rows="3" placeholder="Nhập tin nhắn *" required></textarea>
                    </div>
                </div>

                <div class="chat-contact-actions">
                    <button type="submit" id="chatContactSubmit" class="chat-contact-submit-btn">
                        <i class="ri-send-plane-fill"></i> Gửi tin nhắn
                    </button>
                    <div class="chat-contact-provider">Cung cấp bởi Agrigo Shop</div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
:root {
    --contact-theme: var(--bg-primary, #2ca24d);
}
.chat-contact-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}
.chat-contact-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
}
.chat-contact-wrapper {
    position: relative;
    width: 400px;
    max-width: 90%;
    background: var(--contact-theme);
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: 90vh;
}
.chat-contact-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    z-index: 10;
    padding: 5px;
}
.chat-contact-header-content {
    padding: 25px 25px 15px;
    color: white;
}
.chat-contact-logo-circle {
    width: 56px;
    height: 56px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.chat-contact-logo-circle img {
    width: 56px;
    height: 56px;
    object-fit: cover;
}
.chat-contact-title {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 8px;
}
.chat-contact-desc {
    font-size: 14px;
    line-height: 1.4;
    margin: 0;
    opacity: 0.95;
}
.chat-contact-form-card {
    background: #f5f7fa;
    margin: 0 10px 10px;
    border-radius: 15px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
}
.chat-contact-scroll-area {
    background: white;
    border-radius: 12px;
    padding: 15px;
    overflow-y: auto;
    max-height: 300px;
    margin-bottom: 15px;
}
.form-group {
    margin-bottom: 15px;
}
.form-group:last-child {
    margin-bottom: 0;
}
.form-group label {
    display: block;
    font-size: 14px;
    color: #4b5563;
    margin-bottom: 6px;
    font-weight: 500;
}
.required {
    color: #ef4444;
    margin-left: 3px;
}
.form-group input, .form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f9fafb;
    font-size: 14px;
    transition: all 0.2s;
}
.form-group input:focus, .form-group textarea:focus {
    outline: none;
    border-color: var(--contact-theme);
    background: white;
    box-shadow: 0 0 0 3px rgba(44, 162, 77, 0.1);
}
.chat-contact-actions {
    margin-top: auto;
}
.chat-contact-submit-btn {
    width: 100%;
    background: var(--contact-theme);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: opacity 0.2s;
    box-shadow: 0 4px 12px rgba(44, 162, 77, 0.3);
}
.chat-contact-submit-btn:hover {
    opacity: 0.9;
}
.chat-contact-provider {
    text-align: center;
    font-size: 11px;
    color: #9ca3af;
    margin-top: 8px;
}
/* Custom Scrollbar */
.chat-contact-scroll-area::-webkit-scrollbar {
    width: 6px;
}
.chat-contact-scroll-area::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.chat-contact-scroll-area::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}
</style>

{{-- Initialize chatbot with configuration --}}
<script>
    // Configuration passed from Blade to JavaScript
    window.chatbotConfig = {
        botName: @json($botName),
        avatarUrl: @json($avatarUrl),
        greeting: @json($greeting)
    };
</script>
