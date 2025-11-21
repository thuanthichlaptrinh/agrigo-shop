# Design Document - Chatbot Widget

## Overview

Chatbot widget là một component độc lập được tích hợp vào Laravel Blade templates. Widget sử dụng HTML, CSS và vanilla JavaScript để tạo giao diện chat hiện đại với khả năng thu gọn/mở rộng. Thiết kế tập trung vào tính tái sử dụng, responsive và dễ dàng tùy chỉnh màu sắc theo theme của website.

## Architecture

### Component Structure

```
resources/
├── views/
│   └── partials/
│       └── chatbot-widget.blade.php    # Main widget component
└── js/
    └── chatbot.js                       # Widget logic
public/
└── template/
    └── Assets/
        └── css/
            └── chatbot.css              # Widget styles
```

### Technology Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: Laravel Blade Templates
- **Storage**: Browser SessionStorage for state persistence
- **Icons**: Remix Icon (already used in project)

## Components and Interfaces

### 1. ChatWidget Component (chatbot-widget.blade.php)

Main Blade partial that can be included in any view:

```blade
@include('partials.chatbot-widget', [
    'botName' => 'Organic Shop Support',
    'avatarUrl' => asset('template/Assets/Images/bot-avatar.png'),
    'greeting' => 'Hello! This apartment can be seen tomorrow. Which hour do you prefer?'
])
```

**Structure:**
- Toggle Button (floating button)
- Chat Container (expandable panel)
  - Chat Header
  - Chat Body (messages area)
  - Chat Input (message input + buttons)

### 2. CSS Architecture (chatbot.css)

**CSS Variables for Customization:**
```css
:root {
    --chatbot-primary: #00713b;      /* Website primary green */
    --chatbot-secondary: #4169E1;     /* Blue accent */
    --chatbot-gradient-start: #4169E1;
    --chatbot-gradient-end: #00CED1;
    --chatbot-user-bg: #00713b;
    --chatbot-bot-bg: #F5F5F5;
    --chatbot-text-dark: #333;
    --chatbot-text-light: #fff;
}
```

**Key Classes:**
- `.chatbot-widget`: Main container
- `.chatbot-toggle-btn`: Floating toggle button
- `.chatbot-container`: Expandable chat panel
- `.chatbot-header`: Header with gradient
- `.chatbot-body`: Messages area with scroll
- `.chatbot-message`: Message bubble (bot/user)
- `.chatbot-quick-reply`: Quick reply buttons
- `.chatbot-input-area`: Input section

### 3. JavaScript Module (chatbot.js)

**Core Functions:**

```javascript
class ChatbotWidget {
    constructor(options) {
        this.botName = options.botName;
        this.avatarUrl = options.avatarUrl;
        this.greeting = options.greeting;
        this.isOpen = false;
        this.init();
    }
    
    init() {
        // Initialize widget, load state, bind events
    }
    
    toggleWidget() {
        // Toggle open/close state
    }
    
    sendMessage(text) {
        // Add user message to chat
    }
    
    addBotMessage(text, image, quickReplies) {
        // Add bot message with optional image and quick replies
    }
    
    scrollToBottom() {
        // Auto-scroll to latest message
    }
    
    saveState() {
        // Save open/close state to sessionStorage
    }
    
    loadState() {
        // Load state from sessionStorage
    }
}
```

## Data Models

### Message Object

```javascript
{
    type: 'bot' | 'user',
    text: string,
    image?: string,
    timestamp: Date,
    quickReplies?: Array<{
        text: string,
        value: string
    }>
}
```

### Widget State

```javascript
{
    isOpen: boolean,
    messages: Array<Message>
}
```

## Error Handling

1. **Missing Assets**: Fallback to default avatar if custom avatar fails to load
2. **SessionStorage Unavailable**: Widget works without state persistence
3. **Long Messages**: Implement text truncation with "read more" for messages > 500 characters
4. **Image Load Failure**: Show placeholder or hide image container

## Testing Strategy

### Manual Testing Checklist

1. **Toggle Functionality**
   - Click toggle button opens widget
   - Click again closes widget
   - State persists on page navigation

2. **Message Display**
   - Bot messages align left with gray background
   - User messages align right with green background
   - Images display correctly with rounded corners
   - Long text wraps properly

3. **Quick Reply Buttons**
   - Buttons display horizontally
   - Hover effect works
   - Click sends message as user

4. **Input Functionality**
   - Type and press Enter sends message
   - Click send button sends message
   - Input clears after sending
   - Emoji button displays (placeholder)

5. **Responsive Design**
   - Works on desktop (1920px+)
   - Works on tablet (768px - 1024px)
   - Works on mobile (320px - 767px)
   - Widget scales appropriately

6. **Cross-browser Compatibility**
   - Chrome/Edge (Chromium)
   - Firefox
   - Safari (if available)

7. **Integration Testing**
   - Include in home page
   - Include in product detail page
   - Include in cart page
   - No CSS conflicts with existing styles

## Design Decisions

### 1. Vanilla JavaScript vs Framework
**Decision**: Use vanilla JavaScript
**Rationale**: 
- No additional dependencies
- Lightweight and fast
- Easy to integrate with existing Laravel project
- Project doesn't use Vue/React

### 2. Gradient Colors
**Decision**: Use blue gradient (#4169E1 to #00CED1) for header, green (#00713b) for user messages
**Rationale**:
- Matches the reference design
- Green maintains brand consistency
- Blue provides visual contrast and modern look

### 3. Position and Z-index
**Decision**: Fixed position bottom-right, z-index: 9999
**Rationale**:
- Standard chat widget position
- High z-index ensures visibility above all content
- Doesn't interfere with existing fixed elements

### 4. State Management
**Decision**: Use sessionStorage instead of localStorage
**Rationale**:
- Widget state should reset on new browser session
- Prevents stale state across different visits
- More appropriate for temporary chat sessions

### 5. Component Structure
**Decision**: Single Blade partial with inline CSS/JS option
**Rationale**:
- Easy to include in any view
- Self-contained component
- Can be extracted to separate files if needed
- Follows Laravel conventions

## Visual Design Specifications

### Dimensions
- Toggle Button: 60px × 60px (circle)
- Chat Container: 380px × 600px (desktop), 100% × 100% (mobile < 768px)
- Header Height: 80px
- Input Area Height: 60px
- Message Bubble: Max-width 70%, auto height
- Quick Reply Button: Auto width, 36px height

### Spacing
- Container Padding: 16px
- Message Margin: 12px vertical
- Button Margin: 8px
- Border Radius: 20px (bubbles), 50% (toggle), 8px (container)

### Typography
- Font Family: Inherit from body (Instrument Sans)
- Bot Message: 14px, #333
- User Message: 14px, #fff
- Header Title: 18px, bold, #fff
- Quick Reply: 14px, #4169E1

### Shadows
- Toggle Button: 0 4px 12px rgba(0, 0, 0, 0.15)
- Chat Container: 0 8px 24px rgba(0, 0, 0, 0.2)
- Message Bubble: 0 1px 2px rgba(0, 0, 0, 0.1)

### Animations
- Toggle: 0.3s ease-in-out
- Container Expand: 0.3s cubic-bezier(0.4, 0, 0.2, 1)
- Message Appear: 0.2s ease-out
- Button Hover: 0.2s ease

## Accessibility Considerations

1. **Keyboard Navigation**: Tab through interactive elements
2. **ARIA Labels**: Add aria-label to buttons and input
3. **Focus Indicators**: Visible focus states for all interactive elements
4. **Color Contrast**: Ensure WCAG AA compliance (4.5:1 for text)
5. **Screen Reader**: Announce new messages with aria-live regions
