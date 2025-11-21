# Requirements Document

## Introduction

Hệ thống chatbot widget là một công cụ hỗ trợ khách hàng trực tuyến được tích hợp vào website bán nông sản. Widget này cho phép khách hàng tương tác với hệ thống thông qua giao diện chat hiện đại, có thể thu gọn/mở rộng, và hiển thị trạng thái online. Chatbot hỗ trợ gửi tin nhắn văn bản, hình ảnh, và các nút tương tác nhanh (quick reply buttons).

## Glossary

- **ChatWidget**: Thành phần giao diện chat có thể thu gọn/mở rộng hiển thị ở góc phải màn hình
- **ChatHeader**: Phần đầu của widget hiển thị avatar, tiêu đề và trạng thái online
- **ChatBody**: Khu vực hiển thị các tin nhắn trao đổi giữa người dùng và bot
- **ChatInput**: Khu vực nhập tin nhắn của người dùng
- **QuickReplyButton**: Các nút tương tác nhanh cho phép người dùng chọn câu trả lời có sẵn
- **MessageBubble**: Bong bóng tin nhắn hiển thị nội dung chat
- **ToggleButton**: Nút bấm để mở/đóng chat widget

## Requirements

### Requirement 1

**User Story:** Là một khách hàng truy cập website, tôi muốn thấy một nút chat ở góc phải màn hình để có thể dễ dàng mở chat khi cần hỗ trợ

#### Acceptance Criteria

1. THE ChatWidget SHALL display a floating toggle button at the bottom-right corner of the screen with a fixed position
2. WHEN the user clicks the toggle button, THE ChatWidget SHALL expand to show the full chat interface
3. WHEN the chat interface is open and user clicks the toggle button again, THE ChatWidget SHALL collapse to show only the toggle button
4. THE ToggleButton SHALL use the primary green color (#00713b) matching the website theme
5. THE ToggleButton SHALL display a chat icon when collapsed and a close icon when expanded

### Requirement 2

**User Story:** Là một khách hàng, tôi muốn thấy header của chat hiển thị avatar, tiêu đề và trạng thái online để biết bot đang sẵn sàng hỗ trợ

#### Acceptance Criteria

1. THE ChatHeader SHALL display a gradient background from blue (#4169E1) to cyan (#00CED1)
2. THE ChatHeader SHALL display an avatar image on the left side with circular shape
3. THE ChatHeader SHALL display the text "Chat with us!" next to the avatar
4. THE ChatHeader SHALL display a green dot indicator with the text "We're online!" to show online status
5. THE ChatHeader SHALL include a minimize button (chevron down icon) and a menu button (three dots icon) on the right side

### Requirement 3

**User Story:** Là một khách hàng, tôi muốn thấy tin nhắn từ bot hiển thị rõ ràng với hình ảnh và văn bản để dễ dàng đọc và hiểu

#### Acceptance Criteria

1. THE MessageBubble SHALL display bot messages with a light gray background (#F5F5F5) aligned to the left
2. THE MessageBubble SHALL display user messages with the primary green background (#00713b) aligned to the right with white text
3. WHEN a bot message contains an image, THE ChatBody SHALL display the image with rounded corners above the text message
4. THE MessageBubble SHALL display text with proper padding and readable font size (14px minimum)
5. THE ChatBody SHALL automatically scroll to the latest message when a new message is added

### Requirement 4

**User Story:** Là một khách hàng, tôi muốn có thể chọn các câu trả lời nhanh bằng các nút bấm để tương tác nhanh hơn thay vì phải gõ

#### Acceptance Criteria

1. THE QuickReplyButton SHALL display as rounded pill-shaped buttons below the bot message
2. THE QuickReplyButton SHALL have a white background with blue border (#4169E1) in default state
3. WHEN the user hovers over a QuickReplyButton, THE QuickReplyButton SHALL change background to light blue
4. WHEN the user clicks a QuickReplyButton, THE ChatWidget SHALL send the button text as a user message
5. THE QuickReplyButton SHALL display multiple options in a horizontal row with proper spacing

### Requirement 5

**User Story:** Là một khách hàng, tôi muốn có thể gõ và gửi tin nhắn văn bản để hỏi các câu hỏi tùy chỉnh

#### Acceptance Criteria

1. THE ChatInput SHALL display a text input field with placeholder text "Enter your message..."
2. THE ChatInput SHALL display an emoji picker button on the left side of the input field
3. THE ChatInput SHALL display a send button with arrow icon on the right side using primary blue color (#4169E1)
4. WHEN the user types text and presses Enter key, THE ChatWidget SHALL send the message
5. WHEN the user clicks the send button, THE ChatWidget SHALL send the message and clear the input field

### Requirement 6

**User Story:** Là một khách hàng, tôi muốn widget chat có thể sử dụng trên nhiều trang khác nhau của website mà không cần cấu hình lại

#### Acceptance Criteria

1. THE ChatWidget SHALL be implemented as a reusable component that can be included in any Blade template
2. THE ChatWidget SHALL maintain its state (open/closed) when navigating between pages using session storage
3. THE ChatWidget SHALL load its CSS and JavaScript assets independently without conflicting with existing page styles
4. THE ChatWidget SHALL be responsive and work properly on mobile devices (min-width: 320px)
5. THE ChatWidget SHALL have a z-index value ensuring it appears above all other page content

### Requirement 7

**User Story:** Là một quản trị viên, tôi muốn có thể tùy chỉnh màu sắc và nội dung của chatbot để phù hợp với thương hiệu

#### Acceptance Criteria

1. THE ChatWidget SHALL use CSS variables for primary colors allowing easy customization
2. THE ChatWidget SHALL support configuration for initial greeting message through a data attribute
3. THE ChatWidget SHALL support configuration for avatar image URL through a data attribute
4. THE ChatWidget SHALL support configuration for bot name through a data attribute
5. THE ChatWidget SHALL apply the website's primary green color (#00713b) for user messages and action buttons
