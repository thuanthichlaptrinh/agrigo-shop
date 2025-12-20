# Hướng dẫn WebSocket Chat

## Tổng quan

Hệ thống chat real-time sử dụng Laravel Reverb (WebSocket server) để giao tiếp giữa khách hàng và admin.

## Cài đặt đã hoàn thành

### 1. Backend
- ✅ Laravel Reverb đã được cài đặt
- ✅ Events: `NewChatMessage`, `ConversationUpdated`, `UserTyping`
- ✅ Channels: `chat.conversation.{id}`, `chat.admin`
- ✅ Controllers đã được cập nhật để broadcast events

### 2. Frontend
- ✅ Laravel Echo + Pusher JS đã được cài đặt
- ✅ File `resources/js/echo.js` - Khởi tạo Echo
- ✅ File `resources/js/chat-websocket.js` - Chat WebSocket client

## Cách chạy

### 1. Khởi động WebSocket Server

```bash
php artisan reverb:start
```

Hoặc chạy ở chế độ debug:
```bash
php artisan reverb:start --debug
```

### 2. Khởi động Laravel Server

```bash
php artisan serve
```

### 3. Build Frontend (nếu cần)

```bash
npm run dev
# hoặc
npm run build
```

## Sử dụng trong Frontend

### Cho khách hàng (User)

```javascript
// Khởi tạo kết nối chat
const chat = new ChatWebSocket(conversationId, {
    onMessage: (message) => {
        console.log('Tin nhắn mới:', message);
        // Hiển thị tin nhắn lên UI
    },
    onTyping: (data) => {
        console.log('Đang gõ:', data);
        // Hiển thị "Admin đang gõ..."
    },
    onConnected: () => {
        console.log('Đã kết nối WebSocket');
    },
    onError: (error) => {
        console.error('Lỗi:', error);
    }
});

// Kết nối
chat.connect();

// Gửi trạng thái đang gõ
chat.sendTyping(true);

// Ngắt kết nối khi rời trang
chat.disconnect();
```

### Cho Admin

```javascript
// Khởi tạo kết nối admin
const adminChat = new AdminChatWebSocket({
    onNewMessage: (message) => {
        console.log('Tin nhắn mới từ khách:', message);
        // Cập nhật danh sách cuộc hội thoại
    },
    onConversationUpdate: (conversation, action) => {
        console.log('Cuộc hội thoại cập nhật:', conversation, action);
        // Refresh danh sách
    },
    onConnected: () => {
        console.log('Admin đã kết nối');
    }
});

// Kết nối
adminChat.connect();

// Subscribe vào cuộc hội thoại cụ thể khi mở chat
adminChat.subscribeToConversation(conversationId, {
    onMessage: (message) => {
        // Hiển thị tin nhắn trong chat window
    },
    onTyping: (data) => {
        // Hiển thị "Khách đang gõ..."
    }
});

// Gửi trạng thái đang gõ
adminChat.sendTyping(conversationId, true);

// Ngắt kết nối
adminChat.disconnect();
```

## Cấu trúc Events

### NewChatMessage
```json
{
    "message": {
        "id": 1,
        "conversation_id": 1,
        "sender_id": 1,
        "sender_type": "NguoiDung",
        "sender_name": "Nguyễn Văn A",
        "sender_avatar": "storage/avatars/...",
        "content": "Xin chào",
        "image": null,
        "sent_at": "2025-12-20 10:30:00",
        "is_read": false
    }
}
```

### UserTyping
```json
{
    "conversationId": 1,
    "userId": 1,
    "userName": "Nguyễn Văn A",
    "userType": "NguoiDung",
    "isTyping": true
}
```

### ConversationUpdated
```json
{
    "conversation": {
        "id": 1,
        "user_id": 1,
        "user_name": "Nguyễn Văn A",
        "admin_id": 2,
        "admin_name": "Admin",
        "status": "Mở",
        "last_message": "Xin chào"
    },
    "action": "updated"
}
```

## Cấu hình .env

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=326231
REVERB_APP_KEY=6btqeyqceadvf8juxtha
REVERB_APP_SECRET=uln837cpd3ol4pn6wypi
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## Cách Test WebSocket

### Bước 1: Khởi động servers

Mở 2 terminal:

**Terminal 1 - WebSocket Server:**
```bash
php artisan reverb:start --debug
```

**Terminal 2 - Laravel Server:**
```bash
php artisan serve
```

### Bước 2: Test từ phía User

1. Mở trình duyệt, truy cập trang web (http://127.0.0.1:8000)
2. Mở Developer Tools (F12) → Console
3. Click vào widget chat ở góc phải
4. Gửi tin nhắn
5. Kiểm tra Console có log "WebSocket: Đã kết nối channel"

### Bước 3: Test từ phía Admin

1. Mở tab mới, đăng nhập admin (http://127.0.0.1:8000/admin/chat)
2. Mở Developer Tools → Console
3. Kiểm tra log "WebSocket: Đã kết nối admin channel"
4. Chọn cuộc hội thoại và trả lời

### Bước 4: Kiểm tra Real-time

1. Gửi tin nhắn từ User → Admin nhận ngay lập tức
2. Gửi tin nhắn từ Admin → User nhận ngay lập tức
3. Gõ tin nhắn → Bên kia thấy "đang gõ..."

### Kiểm tra trong Reverb Debug

Khi chạy `php artisan reverb:start --debug`, bạn sẽ thấy:
- Connection established
- Channel subscribed
- Message broadcast

## Troubleshooting

### WebSocket không kết nối được
1. Kiểm tra Reverb server đang chạy: `php artisan reverb:start`
2. Kiểm tra port 8080 không bị chặn
3. Kiểm tra CORS nếu chạy trên domain khác

### Tin nhắn không broadcast
1. Kiểm tra `BROADCAST_CONNECTION=reverb` trong .env
2. Kiểm tra channel authorization trong `routes/channels.php`
3. Xem log: `php artisan reverb:start --debug`

### Authentication lỗi
1. Đảm bảo user đã đăng nhập
2. Kiểm tra route `broadcasting/auth` hoạt động
3. Kiểm tra CSRF token được gửi đúng
