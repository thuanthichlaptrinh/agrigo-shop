{{-- Admin Chat Widget (user-facing) --}}
@php
    $title = $title ?? 'Chat với Admin';
    $statusText = $statusText ?? 'Chúng tôi đang online!';
    $avatarUrl = $avatarUrl ?? asset('template/Assets/Images/chatbot/chatbot3.jpg');
    $adminAvatar = $adminAvatar ?? asset('template/Assets/Images/admin-avatar.png');
    $userAvatar = $userAvatar ?? asset('template/Assets/Images/default-avatar.png');
    $welcomeText = $welcomeText ?? 'Xin chào! Bạn đang được kết nối với bộ phận hỗ trợ.';
@endphp

<div class="chatbot-widget" id="adminChatWidget">
    <!-- Toggle Button -->
    <button class="chatbot-toggle-btn" id="adminChatToggle" aria-label="Toggle chat">
        <img src="{{ asset('template/Assets/Images/chatbot/chatbox.png') }}" alt="Chat" id="adminChatIconImg" class="chatbot-toggle-img">
        <i class="ri-close-line" id="adminChatIcon" aria-hidden="true" style="display: none;"></i>
    </button>

    <!-- Chat Container -->
    <div class="chatbot-container" id="adminChatContainer">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="chatbot-header-content">
                <div class="chatbot-header-left">
                    <img src="{{ $avatarUrl }}"  alt="Admin Avatar" class="chatbot-avatar" onerror="this.src='{{ asset('template/Assets/Images/logo2.png') }}'">
                    <div class="chatbot-header-info">
                        <h3 class="chatbot-title" id="adminChatTitle"><i class="fa-solid fa-headset"></i> {{ $title }}</h3>
                        <div class="chatbot-status">
                            <span class="chatbot-status-dot"></span>
                            <span>{{ $statusText }}</span>
                        </div>
                    </div>
                </div>
                <div class="chatbot-header-actions">
                    <button class="chatbot-header-btn" id="adminChatMinimize" aria-label="Minimize chat">
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                </div>
            </div>
            <svg class="chatbot-wave" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0 C150,80 350,80 600,50 C850,20 1050,20 1200,50 L1200,120 L0,120 Z" fill="#fafafa"></path>
            </svg>
        </div>

        <!-- Body (Messages Area) -->
        <div class="chatbot-body" id="adminChatBody" role="log" aria-live="polite" aria-atomic="false">
            <!-- Messages will be dynamically added here -->
        </div>

        <!-- Input Area -->
        <div class="chatbot-input-area">
            <div class="chatbot-input-wrapper">
                <input 
                    type="text" 
                    class="chatbot-input" 
                    id="adminChatInput" 
                    placeholder="Nhập tin nhắn..."
                    aria-label="Type your message"
                    autocomplete="off"
                >
            </div>
            <button class="chatbot-send-btn" id="adminChatSend" aria-label="Send message">
                <i class="ri-send-plane-fill"></i>
            </button>
        </div>
    </div>
</div>

{{-- Admin chat configuration --}}
<script>
    window.adminChatConfig = {
        chatApiUrl: '/api/v1/chat',
        adminAvatar: @json($adminAvatar),
        userAvatar: @json($userAvatar),
        welcomeText: @json($welcomeText),
    };
</script>
