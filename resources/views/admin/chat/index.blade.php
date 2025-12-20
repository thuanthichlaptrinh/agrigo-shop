@extends('admin.layouts.app')

@section('title', 'Hỗ trợ khách hàng - Admin')

@section('content')
<style>
    :root {
        --bg: #f5f7fb;
        --panel: #ffffff;
        --primary: #2563eb;
        --primary-2: #1d4ed8;
        --success: #16a34a;
        --warning: #f59e0b;
        --muted: #6b7280;
        --border: #e5e7eb;
        --shadow: 0 14px 45px rgba(15, 23, 42, 0.08);
    }

    .chat-container {
        display: flex;
        height: calc(100vh - 140px);
        background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow);
        border: 1px solid #e2e8f0;
    }

    /* Sidebar - Danh sách cuộc hội thoại */
    .chat-sidebar {
        width: 430px;
        background: var(--panel);
        border-right: 1px solid var(--border);
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
    }

    .sidebar-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.2px;
    }

    .chat-stats {
        display: flex;
        gap: 10px;
    }

    .stat-badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        background: #f8fafc;
        color: var(--muted);
        border: 1px solid var(--border);
    }

    .stat-badge.waiting {
        background: #fff7ed;
        color: var(--warning);
        border-color: #fcd34d;
    }

    .stat-badge.active {
        background: #ecfdf3;
        color: var(--success);
        border-color: #bbf7d0;
    }

    .sidebar-filters {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 8px;
    }

    .filter-btn {
        padding: 10px 12px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid var(--border);
        background: #f8fafc;
        color: var(--muted);
        cursor: pointer;
        transition: all 0.18s ease;
    }

    .filter-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #e0e7ff;
    }

    .filter-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
    }

    .conversation-list {
        flex: 1;
        overflow-y: auto;
    }

    .conversation-item {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .conversation-item:hover {
        background: #f8fafc;
    }

    .conversation-item.active {
        background: #eef2ff;
        border-left: 3px solid var(--primary);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
    }

    .conversation-item.unread {
        background: #fef2f2;
        border-left: 3px solid #ef4444;
    }

    .conversation-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--primary-2));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.18);
    }

    .conversation-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 12px;
        object-fit: cover;
    }

    .conversation-info {
        flex: 1;
        min-width: 0;
    }

    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .conversation-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conversation-time {
        font-size: 11px;
        color: #9ca3af;
        flex-shrink: 0;
    }

    .conversation-preview {
        font-size: 13px;
        color: var(--muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conversation-status {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
    }

    .status-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
    }

    .status-dot.waiting { background: var(--warning); }
    .status-dot.active { background: var(--success); }
    .status-dot.closed { background: #9ca3af; }

    .status-label {
        font-size: 11px;
        color: var(--muted);
    }

    .unread-badge {
        background: #ef4444;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
        margin-left: auto;
        box-shadow: 0 6px 14px rgba(239, 68, 68, 0.2);
    }

    /* Main Chat Area */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fbfdff;
    }

    .chat-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .chat-user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(22, 163, 74, 0.22);
    }

    .chat-user-details h3 {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 2px 0;
    }

    .chat-user-details p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }

    .chat-user-meta {
        font-size: 12px;
        color: #475569;
        margin-top: 4px;
        font-weight: 600;
    }

    .chat-actions {
        display: flex;
        gap: 10px;
    }

    .chat-action-btn {
        padding: 10px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid var(--border);
        background: #fff;
        color: #1f2937;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }

    .chat-action-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-1px);
    }

    .chat-action-btn.primary {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.25);
    }

    .chat-action-btn.primary:hover { background: var(--primary-2); }

    .chat-action-btn.danger {
        color: #ef4444;
        border-color: #fecaca;
        background: #fff5f5;
    }

    .chat-action-btn.danger:hover {
        background: #fee2e2;
    }

    /* Messages Area */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
    }

    .chat-messages::-webkit-scrollbar { width: 8px; }
    .chat-messages::-webkit-scrollbar-track { background: #e5e7eb; border-radius: 999px; }
    .chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
    .chat-messages::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    .message-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .message {
        display: flex;
        gap: 10px;
        max-width: 70%;
    }

    .message.user { align-self: flex-start; background: transparent; }
    .message.admin { align-self: flex-end; flex-direction: row-reverse; background: transparent;}

    .message-avatar {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
    }

    .message.user .message-avatar { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .message.admin .message-avatar { background: linear-gradient(135deg, var(--primary), var(--primary-2)); }

    .message-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        object-fit: cover;
    }

    .message-content {
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.55;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
    }

    .message.user .message-content {
        background-color: #e5e7eb;
        color: black;
        border-radius: 18px 18px 18px 6px;
        box-shadow: 0 12px 26px rgba(149, 172, 222, 0.22);
    }

    .message.admin .message-content {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        border-radius: 18px 18px 6px 18px;
        box-shadow: 0 12px 26px rgba(41, 22, 163, 0.25);
    }

    .message-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 4px;
        padding: 0 16px;
    }

    .message.admin .message-time { text-align: right; }

    .message-image {
        max-width: 250px;
        border-radius: 12px;
        margin-top: 8px;
    }

    /* System Message */
    .system-message {
        text-align: center;
        padding: 8px;
    }

    .system-message span {
        background: #e5e7eb;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        color: #4b5563;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
    }

    /* Chat Input */
    .chat-input-area {
        padding: 16px 24px;
        border-top: 1px solid #e5e7eb;
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        position: sticky;
        bottom: 0;
        z-index: 2;
    }

    .chat-input-wrapper {
        display: flex;
        gap: 12px;
        align-items: flex-end;
    }

    .chat-input-container {
        flex: 1;
        display: flex;
        align-items: flex-end;
        background: #fff;
        border-radius: 24px;
        padding: 10px 16px;
        transition: all 0.2s;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    }

    .chat-input-container:focus-within {
        background: #fff;
        border-color: #cbd5e1;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .chat-input {
        flex: 1;
        border: none;
        background: none;
        padding: 8px 0;
        font-size: 14px;
        color: #1f2937;
        resize: none;
        max-height: 100px;
        line-height: 1.5;
    }

    .chat-input:focus {
        outline: none;
    }

    .chat-input::placeholder {
        color: #9ca3af;
    }

    .input-actions {
        display: flex;
        gap: 4px;
        padding-bottom: 4px;
    }

    .input-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: none;
        color: #6b7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .input-action-btn:hover {
        background: #e5e7eb;
        color: #22c55e;
    }

    .send-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
    }

    .send-btn:disabled {
        background: #e5e7eb;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Empty State */
    .empty-state {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        text-align: center;
        padding: 40px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .empty-state-icon svg {
        width: 40px;
        height: 40px;
        color: #9ca3af;
    }

    .empty-state h3 {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin: 0 0 8px 0;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .chat-container {
            flex-direction: column;
            height: calc(100vh - 100px);
        }

        .chat-sidebar {
            width: 100%;
            height: 40%;
        }

        .chat-main {
            height: 60%;
        }
    }

    /* Loading Indicator */
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 12px 16px;
        background: #f3f4f6;
        border-radius: 18px;
        width: fit-content;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #9ca3af;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-6px); }
    }
</style>

<div class="chat-container">
    <!-- Sidebar - Danh sách cuộc hội thoại -->
    <div class="chat-sidebar">
        <div class="sidebar-header">
            <h2>💬 Hỗ trợ khách hàng</h2>
            <div class="chat-stats">
                <span class="stat-badge waiting" id="waitingCount">0 chờ</span>
                <span class="stat-badge active" id="activeCount">0 đang xử lý</span>
            </div>
        </div>

        <div class="sidebar-filters">
            <button class="filter-btn active" data-filter="all">Tất cả</button>
            <button class="filter-btn" data-filter="waiting">Chờ xử lý</button>
            <button class="filter-btn" data-filter="open">Đang xử lý</button>
            <button class="filter-btn" data-filter="closed">Đã đóng</button>
        </div>

        <div class="conversation-list" id="conversationList">
            <!-- Conversations will be loaded here -->
            <div class="empty-state" id="noConversations">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3>Chưa có cuộc hội thoại</h3>
                <p>Các yêu cầu hỗ trợ từ khách hàng sẽ hiển thị ở đây</p>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main">
        <div class="empty-state" id="selectConversation">
            <div class="empty-state-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
            </div>
            <h3>Chọn một cuộc hội thoại</h3>
            <p>Chọn cuộc hội thoại từ danh sách bên trái để bắt đầu hỗ trợ</p>
        </div>

        <!-- Chat Content (hidden by default) -->
        <div id="chatContent" style="display: none; flex-direction: column; height: 100%;">
            <div class="chat-header">
                <div class="chat-user-info">
                    <div class="chat-user-avatar" id="chatUserAvatar">
                        <span id="chatUserInitial">K</span>
                    </div>
                    <div class="chat-user-details">
                        <h3 id="chatUserName">Khách hàng</h3>
                        <p id="chatUserStatus">Đang hoạt động</p>
                        <p class="chat-user-meta" id="chatUserMeta">ID: N/A</p>
                    </div>
                </div>
                <div class="chat-actions">
                    <button class="chat-action-btn" id="assignBtn" title="Nhận xử lý">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Nhận xử lý
                    </button>
                    <button class="chat-action-btn danger" id="closeConvBtn" title="Đóng hội thoại">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Đóng
                    </button>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be loaded here -->
            </div>

            <div class="chat-input-area">
                <div class="chat-input-wrapper">
                    <div class="chat-input-container">
                        <textarea class="chat-input" id="messageInput" placeholder="Nhập tin nhắn..." rows="1"></textarea>
                        <div class="input-actions">
                            <button class="input-action-btn" id="attachImageBtn" title="Đính kèm hình ảnh">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button class="send-btn" id="sendBtn" title="Gửi tin nhắn">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const AdminChat = {
        currentConversationId: null,
        conversations: [],
        currentFilter: 'all',
        refreshInterval: null,
        adminId: {{ $authUser->ID ?? 'null' }},
        wsChannel: null,
        wsAdminChannel: null,
        isWebSocketConnected: false,
        typingTimeout: null,
        isUserTyping: false,
        pollingInterval: null,
        messagePollingInterval: null,
        isSending: false,
        lastTypingStatus: false,

            normalizeAvatar(url) {
                if (!url) return null;
                const trimmed = url.trim();
                if (!trimmed) return null;
                if (trimmed.startsWith('http')) return trimmed;
                if (trimmed.startsWith('/')) return trimmed;
                return `/${trimmed}`; // relative path like uploads/avatars/... -> /uploads/avatars/...
            },

        init() {
            this.bindEvents();
            this.loadConversations();
            this.loadStats();
            this.connectWebSocket();
            
            // Fallback polling - sẽ được điều chỉnh dựa trên WebSocket status
            this.startPolling();
        },

        startPolling() {
            // Polling cho danh sách cuộc hội thoại
            if (this.pollingInterval) clearInterval(this.pollingInterval);
            
            // Nếu WebSocket kết nối, polling chậm hơn (30s), nếu không thì nhanh hơn (5s)
            const interval = this.isWebSocketConnected ? 30000 : 5000;
            
            this.pollingInterval = setInterval(() => {
                this.loadConversations();
                this.loadStats();
            }, interval);
        },

        startMessagePolling() {
            // Polling cho tin nhắn trong cuộc hội thoại hiện tại
            if (this.messagePollingInterval) clearInterval(this.messagePollingInterval);
            
            if (!this.isWebSocketConnected && this.currentConversationId) {
                this.messagePollingInterval = setInterval(() => {
                    if (this.currentConversationId) {
                        this.loadMessages(this.currentConversationId);
                    }
                }, 3000);
            }
        },

        stopMessagePolling() {
            if (this.messagePollingInterval) {
                clearInterval(this.messagePollingInterval);
                this.messagePollingInterval = null;
            }
        },

        // ==================== WebSocket Methods ====================
        
        connectWebSocket() {
            if (!window.Echo) {
                console.warn('WebSocket không khả dụng, sử dụng polling');
                return;
            }

            try {
                // Subscribe vào admin channel để nhận tất cả tin nhắn mới
                this.wsAdminChannel = window.Echo.private('chat.admin');

                // Lắng nghe tin nhắn mới từ tất cả cuộc hội thoại
                this.wsAdminChannel.listen('.message.new', (data) => {
                    this.handleNewMessage(data.message);
                });

                // Lắng nghe cập nhật cuộc hội thoại
                this.wsAdminChannel.listen('.conversation.updated', (data) => {
                    this.handleConversationUpdate(data.conversation, 'updated');
                });

                this.wsAdminChannel.listen('.conversation.closed', (data) => {
                    this.handleConversationUpdate(data.conversation, 'closed');
                });

                this.wsAdminChannel.subscribed(() => {
                    console.log('WebSocket: Đã kết nối admin channel');
                    this.isWebSocketConnected = true;
                    this.updateConnectionStatus(true);
                    // Điều chỉnh polling interval khi WebSocket kết nối
                    this.startPolling();
                    this.stopMessagePolling();
                });

                this.wsAdminChannel.error((error) => {
                    console.error('WebSocket error:', error);
                    this.isWebSocketConnected = false;
                    this.updateConnectionStatus(false);
                    // Tăng tốc polling khi WebSocket lỗi
                    this.startPolling();
                    this.startMessagePolling();
                });

            } catch (err) {
                console.error('WebSocket connection error:', err);
            }
        },

        subscribeToConversation(conversationId) {
            if (!window.Echo || !conversationId) return;

            // Unsubscribe từ channel cũ
            if (this.wsChannel) {
                window.Echo.leave(`chat.conversation.${this.currentConversationId}`);
            }

            // Subscribe vào channel mới
            this.wsChannel = window.Echo.private(`chat.conversation.${conversationId}`);

            this.wsChannel.listen('.message.new', (data) => {
                // Bỏ qua tin nhắn từ admin (đã hiển thị qua optimistic UI)
                const senderType = data.message?.sender_type || data.message?.LoaiNguoiGui;
                if (senderType === 'Admin') {
                    return;
                }
                
                if (data.message.conversation_id === this.currentConversationId || 
                    data.message.IDCuocHoiThoai === this.currentConversationId) {
                    this.appendMessage(data.message);
                }
            });

            this.wsChannel.listen('.user.typing', (data) => {
                this.handleTypingStatus(data);
            });
        },

        handleNewMessage(message) {
            // Bỏ qua tin nhắn từ chính admin này (đã hiển thị qua optimistic UI)
            const senderType = message.sender_type || message.LoaiNguoiGui;
            if (senderType === 'Admin') {
                // Tin nhắn từ admin đã được hiển thị qua optimistic UI
                // Chỉ cập nhật sidebar nhẹ nhàng (không reload toàn bộ)
                this.updateConversationPreview(message);
                return;
            }

            // Cập nhật danh sách cuộc hội thoại (chỉ khi tin nhắn từ user)
            this.loadConversations();
            this.loadStats();

            // Nếu đang xem cuộc hội thoại này, thêm tin nhắn
            const convId = message.conversation_id || message.IDCuocHoiThoai;
            if (convId === this.currentConversationId) {
                this.appendMessage(message);
            }

            // Phát âm thanh thông báo nếu tin nhắn từ user
            if (senderType === 'NguoiDung') {
                this.playNotificationSound();
            }
        },

        // Cập nhật preview tin nhắn mới nhất trong sidebar mà không reload toàn bộ
        updateConversationPreview(message) {
            const convId = message.conversation_id || message.IDCuocHoiThoai;
            const content = message.content || message.NoiDung;
            
            // Tìm conversation item trong sidebar
            const convItem = document.querySelector(`.conversation-item[data-id="${convId}"]`);
            if (convItem) {
                const previewEl = convItem.querySelector('.conversation-preview');
                const timeEl = convItem.querySelector('.conversation-time');
                
                if (previewEl) {
                    previewEl.textContent = content.substring(0, 50);
                }
                if (timeEl) {
                    timeEl.textContent = 'Vừa xong';
                }
            }
        },

        appendMessage(message) {
            const container = document.getElementById('chatMessages');
            if (!container) return;

            // Chuyển đổi format
            const msg = {
                ID: message.id || message.ID,
                LoaiNguoiGui: message.sender_type || message.LoaiNguoiGui,
                NoiDung: message.content || message.NoiDung,
                HinhAnh: message.image || message.HinhAnh,
                ThoiGianGui: message.sent_at || message.ThoiGianGui,
                nguoi_gui: {
                    TenNguoiDung: message.sender_name,
                    HinhAnh: message.sender_avatar
                }
            };

            // Kiểm tra tin nhắn đã tồn tại chưa
            if (container.querySelector(`[data-message-id="${msg.ID}"]`)) return;

            // Ẩn typing indicator
            this.hideTypingIndicator();

            const isAdmin = msg.LoaiNguoiGui === 'Admin';
            const isSystem = msg.LoaiNguoiGui === 'HeThong';

            let html = '';
            if (isSystem) {
                html = `
                    <div class="system-message" data-message-id="${msg.ID}">
                        <span>${this.escapeHtml(msg.NoiDung)}</span>
                    </div>
                `;
            } else {
                const senderName = msg.nguoi_gui?.TenNguoiDung || (isAdmin ? 'Admin' : 'Khách');
                const initial = senderName.charAt(0).toUpperCase();
                const avatar = this.normalizeAvatar(msg.nguoi_gui?.HinhAnh);
                const time = this.formatTime(msg.ThoiGianGui);

                html = `
                    <div class="message-group" data-message-id="${msg.ID}">
                        <div class="message ${isAdmin ? 'admin' : 'user'}">
                            <div class="message-avatar">
                                ${avatar ? `<img src="${avatar}" alt="" style="width:34px;height:34px;object-fit:cover;">` : initial}
                            </div>
                            <div>
                                <div class="message-content">
                                    ${this.escapeHtml(msg.NoiDung)}
                                    ${msg.HinhAnh ? `<img src="${msg.HinhAnh}" class="message-image" alt="">` : ''}
                                </div>
                                <div class="message-time">${time}</div>
                            </div>
                        </div>
                    </div>
                `;
            }

            container.insertAdjacentHTML('beforeend', html);
            container.scrollTop = container.scrollHeight;
        },

        handleConversationUpdate(conversation, action) {
            this.loadConversations();
            this.loadStats();
        },

        handleTypingStatus(data) {
            if (data.userType === 'NguoiDung' || data.user_type === 'NguoiDung') {
                if (data.isTyping || data.is_typing) {
                    this.showTypingIndicator(data.userName || data.user_name || 'Khách');
                } else {
                    this.hideTypingIndicator();
                }
            }
        },

        showTypingIndicator(name = 'Khách') {
            if (this.isUserTyping) return;
            this.isUserTyping = true;

            this.hideTypingIndicator();

            const container = document.getElementById('chatMessages');
            if (!container) return;

            const html = `
                <div class="message-group" id="typing-indicator">
                    <div class="message user">
                        <div class="message-avatar">K</div>
                        <div>
                            <div class="typing-indicator">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="message-time">${this.escapeHtml(name)} đang gõ...</div>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            container.scrollTop = container.scrollHeight;
        },

        hideTypingIndicator() {
            this.isUserTyping = false;
            const indicator = document.getElementById('typing-indicator');
            if (indicator) indicator.remove();
        },

        sendTypingStatus(isTyping = true) {
            if (!this.currentConversationId) return;

            // Debounce - chỉ gửi nếu trạng thái thay đổi
            if (this.lastTypingStatus === isTyping && isTyping) {
                // Đang gõ và đã gửi rồi, chỉ reset timeout
                if (this.typingTimeout) {
                    clearTimeout(this.typingTimeout);
                }
                this.typingTimeout = setTimeout(() => {
                    this.sendTypingStatus(false);
                }, 3000);
                return;
            }

            this.lastTypingStatus = isTyping;

            if (this.typingTimeout) {
                clearTimeout(this.typingTimeout);
            }

            fetch('/admin/chat/typing', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    conversation_id: this.currentConversationId,
                    is_typing: isTyping
                })
            }).catch(console.error);

            if (isTyping) {
                this.typingTimeout = setTimeout(() => {
                    this.sendTypingStatus(false);
                }, 3000);
            }
        },

        updateConnectionStatus(connected) {
            const waitingBadge = document.getElementById('waitingCount');
            if (waitingBadge) {
                waitingBadge.title = connected ? 'WebSocket đã kết nối' : 'Đang sử dụng polling';
            }
        },

        playNotificationSound() {
            try {
                const audio = new Audio('/template/Assets/sounds/notification.mp3');
                audio.volume = 0.5;
                audio.play().catch(() => {});
            } catch (e) {}
        },

        bindEvents() {
            // Filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    this.currentFilter = e.target.dataset.filter;
                    this.renderConversations();
                });
            });

            // Send message
            document.getElementById('sendBtn').addEventListener('click', () => this.sendMessage());
            document.getElementById('messageInput').addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });

            // Auto-resize textarea
            document.getElementById('messageInput').addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';
                // Gửi trạng thái đang gõ
                AdminChat.sendTypingStatus(true);
            });

            // Assign conversation
            document.getElementById('assignBtn').addEventListener('click', () => {
                if (this.currentConversationId) {
                    this.assignConversation(this.currentConversationId);
                }
            });

            // Close conversation
            document.getElementById('closeConvBtn').addEventListener('click', () => {
                if (this.currentConversationId && confirm('Bạn có chắc muốn đóng cuộc hội thoại này?')) {
                    this.closeConversation(this.currentConversationId);
                }
            });
        },

        async loadConversations() {
            try {
                const response = await fetch('/admin/chat/conversations');
                const data = await response.json();
                
                if (data.success) {
                    this.conversations = data.conversations?.data || [];
                    this.renderConversations();
                }
            } catch (error) {
                console.error('Error loading conversations:', error);
            }
        },

        async loadStats() {
            try {
                const response = await fetch('/admin/chat/conversations/stats');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('waitingCount').textContent = data.stats.waiting + ' chờ';
                    document.getElementById('activeCount').textContent = data.stats.open + ' đang xử lý';
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        },

        renderConversations() {
            const list = document.getElementById('conversationList');
            const noConversations = document.getElementById('noConversations');
            
            // Null check
            if (!list) return;
            
            let filtered = this.conversations;
            if (this.currentFilter === 'waiting') {
                filtered = this.conversations.filter(c => c.TrangThai === 'Chờ');
            } else if (this.currentFilter === 'open') {
                filtered = this.conversations.filter(c => c.TrangThai === 'Mở');
            } else if (this.currentFilter === 'closed') {
                filtered = this.conversations.filter(c => c.TrangThai === 'Đóng');
            }

            if (filtered.length === 0) {
                list.innerHTML = '';
                if (noConversations) {
                    list.appendChild(noConversations);
                    noConversations.style.display = 'flex';
                }
                return;
            }

            if (noConversations) {
                noConversations.style.display = 'none';
            }
            
            list.innerHTML = filtered.map(conv => {
                const isActive = conv.ID === this.currentConversationId;
                const userName = conv.nguoi_dung?.TenNguoiDung || 'Khách';
                const initial = userName.charAt(0).toUpperCase();
                const lastMessage = conv.tin_nhan_moi_nhat?.NoiDung || 'Chưa có tin nhắn';
                const time = conv.tin_nhan_moi_nhat ? this.formatTime(conv.tin_nhan_moi_nhat.ThoiGianGui) : '';
                const statusClass = conv.TrangThai === 'Chờ' ? 'waiting' : (conv.TrangThai === 'Mở' ? 'active' : 'closed');
                const statusLabel = conv.TrangThai === 'Chờ' ? 'Chờ xử lý' : (conv.TrangThai === 'Mở' ? 'Đang xử lý' : 'Đã đóng');
                const unreadCount = conv.chua_doc || 0;
                const avatar = this.normalizeAvatar(conv.nguoi_dung?.HinhAnh);

                return `
                    <div class="conversation-item ${isActive ? 'active' : ''} ${unreadCount > 0 ? 'unread' : ''}" 
                         data-id="${conv.ID}" onclick="AdminChat.selectConversation(${conv.ID})">
                        <div class="conversation-avatar">
                            ${avatar ? `<img src="${avatar}" alt="" style="width:42px;height:42px;object-fit:cover;">` : initial}
                        </div>
                        <div class="conversation-info">
                            <div class="conversation-header">
                                <span class="conversation-name">${this.escapeHtml(userName)}</span>
                                <span class="conversation-time">${time}</span>
                            </div>
                            <div class="conversation-preview">${this.escapeHtml(lastMessage.substring(0, 50))}</div>
                            <div class="conversation-status">
                                <span class="status-dot ${statusClass}"></span>
                                <span class="status-label">${statusLabel}</span>
                                ${unreadCount > 0 ? `<span class="unread-badge">${unreadCount}</span>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        },

        async selectConversation(id) {
            this.currentConversationId = id;
            
            // Subscribe WebSocket cho cuộc hội thoại này
            this.subscribeToConversation(id);
            
            // Bắt đầu message polling nếu WebSocket không kết nối
            if (!this.isWebSocketConnected) {
                this.startMessagePolling();
            }
            
            // Update UI
            document.getElementById('selectConversation').style.display = 'none';
            document.getElementById('chatContent').style.display = 'flex';
            
            // Update active state in list
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.toggle('active', parseInt(item.dataset.id) === id);
            });

            // Find conversation info
            const conv = this.conversations.find(c => c.ID === id);
            if (conv) {
                const userName = conv.nguoi_dung?.TenNguoiDung || 'Khách';
                const userId = conv.nguoi_dung?.ID || 'N/A';
                const initial = userName.charAt(0).toUpperCase();
                const avatar = this.normalizeAvatar(conv.nguoi_dung?.HinhAnh);
                
                document.getElementById('chatUserName').textContent = userName;
                document.getElementById('chatUserStatus').textContent = 
                    conv.TrangThai === 'Chờ' ? 'Chờ xử lý' : (conv.TrangThai === 'Mở' ? 'Đang được hỗ trợ' : 'Đã đóng');
                document.getElementById('chatUserMeta').textContent = `ID: ${userId}`;
                
                if (avatar) {
                    document.getElementById('chatUserAvatar').innerHTML = `<img src="${avatar}" alt="" style="width:44px;height:44px;object-fit:cover;">`;
                } else {
                    document.getElementById('chatUserAvatar').innerHTML = `<span>${initial}</span>`;
                }

                // Update action buttons
                const assignBtn = document.getElementById('assignBtn');
                const closeBtn = document.getElementById('closeConvBtn');
                
                if (conv.TrangThai === 'Đóng') {
                    assignBtn.style.display = 'none';
                    closeBtn.style.display = 'none';
                    document.getElementById('messageInput').disabled = true;
                    document.getElementById('sendBtn').disabled = true;
                } else {
                    assignBtn.style.display = conv.IDAdmin ? 'none' : 'flex';
                    closeBtn.style.display = 'flex';
                    document.getElementById('messageInput').disabled = false;
                    document.getElementById('sendBtn').disabled = false;
                }
            }

            await this.loadMessages(id);
        },

        async loadMessages(conversationId) {
            try {
                const response = await fetch(`/admin/chat/conversations/${conversationId}/messages`);
                const data = await response.json();
                
                if (data.success) {
                    this.renderMessages(data.messages || []);
                }
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        },

        renderMessages(messages) {
            const container = document.getElementById('chatMessages');
            
            if (messages.length === 0) {
                container.innerHTML = `
                    <div class="system-message">
                        <span>Cuộc hội thoại bắt đầu</span>
                    </div>
                `;
                return;
            }

            container.innerHTML = messages.map(msg => {
                const isAdmin = msg.LoaiNguoiGui === 'Admin';
                const isSystem = msg.LoaiNguoiGui === 'HeThong';
                const isBot = msg.LoaiNguoiGui === 'Bot';

                if (isSystem) {
                    return `
                        <div class="system-message">
                            <span>${this.escapeHtml(msg.NoiDung)}</span>
                        </div>
                    `;
                }

                const senderName = msg.nguoi_gui?.TenNguoiDung || (isBot ? 'Trợ lý ảo' : (isAdmin ? 'Admin' : 'Khách'));
                const initial = senderName.charAt(0).toUpperCase();
                const avatar = this.normalizeAvatar(msg.nguoi_gui?.HinhAnh) || (isAdmin ? "{{ asset('template/Assets/Images/admin-avatar.png') }}" : "{{ asset('template/Assets/Images/default-avatar.png') }}");
                const time = this.formatTime(msg.ThoiGianGui);

                return `
                    <div class="message-group">
                        <div class="message ${isAdmin || isBot ? 'admin' : 'user'}">
                            <div class="message-avatar">
                                ${avatar ? `<img src="${avatar}" alt="" style="width:34px;height:34px;object-fit:cover;">` : initial}
                            </div>
                            <div>
                                <div class="message-content">
                                    ${this.escapeHtml(msg.NoiDung)}
                                    ${msg.HinhAnh ? `<img src="${msg.HinhAnh}" class="message-image" alt="">` : ''}
                                </div>
                                <div class="message-time">${time}</div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            container.scrollTop = container.scrollHeight;
        },

        async sendMessage() {
            const input = document.getElementById('messageInput');
            const sendBtn = document.getElementById('sendBtn');
            const content = input.value.trim();
            
            if (!content || !this.currentConversationId) {
                console.log('sendMessage: Missing content or conversationId', { content, conversationId: this.currentConversationId });
                return;
            }

            // Prevent double send
            if (this.isSending) {
                console.log('sendMessage: Already sending');
                return;
            }
            this.isSending = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.error('CSRF token not found');
                alert('Lỗi: Không tìm thấy CSRF token. Vui lòng refresh trang.');
                this.isSending = false;
                return;
            }

            // Clear input và disable button ngay lập tức
            input.value = '';
            input.style.height = 'auto';
            if (sendBtn) sendBtn.disabled = true;

            // Optimistic UI - hiển thị tin nhắn ngay lập tức
            const tempId = 'temp-' + Date.now();
            const adminAvatar = '{{ $authUser->HinhAnh ?? "" }}';
            const adminName = '{{ $authUser->TenNguoiDung ?? "Admin" }}';
            const tempMessage = {
                id: tempId,
                ID: tempId,
                sender_type: 'Admin',
                LoaiNguoiGui: 'Admin',
                content: content,
                NoiDung: content,
                sent_at: new Date().toISOString(),
                ThoiGianGui: new Date().toISOString(),
                sender_name: adminName,
                sender_avatar: adminAvatar,
                nguoi_gui: { 
                    TenNguoiDung: adminName,
                    HinhAnh: adminAvatar
                }
            };
            
            console.log('Optimistic UI: Hiển thị tin nhắn ngay', tempMessage);
            // Hiển thị tin nhắn tạm ngay lập tức
            this.appendMessage(tempMessage);
            
            // Cập nhật preview trong sidebar ngay lập tức
            this.updateConversationPreview(tempMessage);

            console.log('Sending message:', { conversation_id: this.currentConversationId, message: content });

            try {
                const response = await fetch('/admin/chat/messages/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        conversation_id: this.currentConversationId,
                        message: content
                    })
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error:', response.status, errorText);
                    // Xóa tin nhắn tạm nếu lỗi
                    const tempEl = document.querySelector(`[data-message-id="${tempId}"]`);
                    if (tempEl) tempEl.remove();
                    alert(`Lỗi server: ${response.status}`);
                    return;
                }

                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success && data.message) {
                    // Cập nhật tin nhắn tạm với ID thật
                    const tempEl = document.querySelector(`[data-message-id="${tempId}"]`);
                    if (tempEl) {
                        tempEl.setAttribute('data-message-id', data.message.ID);
                    }
                } else if (!data.success) {
                    // Xóa tin nhắn tạm nếu lỗi
                    const tempEl = document.querySelector(`[data-message-id="${tempId}"]`);
                    if (tempEl) tempEl.remove();
                    alert(data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                // Xóa tin nhắn tạm nếu lỗi
                const tempEl = document.querySelector(`[data-message-id="${tempId}"]`);
                if (tempEl) tempEl.remove();
                alert('Không thể gửi tin nhắn: ' + error.message);
            } finally {
                // Reset sending state và enable button
                this.isSending = false;
                const sendBtn = document.getElementById('sendBtn');
                if (sendBtn) sendBtn.disabled = false;
            }
        },

        async assignConversation(id) {
            try {
                const response = await fetch(`/admin/chat/conversations/${id}/assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    await this.loadConversations();
                    await this.selectConversation(id);
                    this.loadStats();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                console.error('Error assigning conversation:', error);
            }
        },

        async closeConversation(id) {
            try {
                const response = await fetch(`/admin/chat/conversations/${id}/close`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    await this.loadConversations();
                    this.loadStats();
                    
                    // Reset chat view
                    this.currentConversationId = null;
                    document.getElementById('selectConversation').style.display = 'flex';
                    document.getElementById('chatContent').style.display = 'none';
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                console.error('Error closing conversation:', error);
            }
        },

        formatTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Vừa xong';
            if (diff < 3600000) return Math.floor(diff / 60000) + ' phút trước';
            if (diff < 86400000) return Math.floor(diff / 3600000) + ' giờ trước';
            
            return date.toLocaleDateString('vi-VN', { 
                day: '2-digit', 
                month: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // Make globally accessible
    window.AdminChat = AdminChat;
    AdminChat.init();
});
</script>
@endsection
