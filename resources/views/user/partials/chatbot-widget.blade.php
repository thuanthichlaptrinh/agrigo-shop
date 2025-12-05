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
                        <h3 class="chatbot-title">Chat với trợ lý ảo!</h3>
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

{{-- Initialize chatbot with configuration --}}
<script>
    // Configuration passed from Blade to JavaScript
    window.chatbotConfig = {
        botName: @json($botName),
        avatarUrl: @json($avatarUrl),
        greeting: @json($greeting)
    };
</script>
