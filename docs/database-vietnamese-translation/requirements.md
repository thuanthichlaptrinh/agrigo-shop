# Requirements Document

## Introduction

Chuyển đổi schema database từ tiếng Anh sang tiếng Việt cho hệ thống quản lý bán hàng nông sản. Việc chuyển đổi bao gồm tên bảng, tên cột, giá trị ENUM, và comments để đảm bảo database hoàn toàn sử dụng tiếng Việt, phù hợp với đội ngũ phát triển và dễ bảo trì.

## Glossary

- **Database Schema**: Cấu trúc định nghĩa các bảng, cột, kiểu dữ liệu và quan hệ trong cơ sở dữ liệu
- **ENUM Values**: Các giá trị cố định được định nghĩa cho một cột (ví dụ: 'pending', 'confirmed')
- **Foreign Key**: Khóa ngoại liên kết giữa các bảng
- **Index**: Chỉ mục để tối ưu hóa truy vấn
- **Constraint**: Ràng buộc dữ liệu (khóa chính, khóa ngoại, unique, check)

## Requirements

### Requirement 1

**User Story:** Là một developer, tôi muốn tất cả tên bảng được chuyển sang tiếng Việt, để dễ hiểu và bảo trì code

#### Acceptance Criteria

1. THE Database Schema SHALL chuyển đổi tất cả tên bảng từ tiếng Anh sang tiếng Việt với quy tắc snake_case
2. THE Database Schema SHALL giữ nguyên cấu trúc và quan hệ giữa các bảng sau khi đổi tên
3. THE Database Schema SHALL cập nhật tất cả foreign key constraints để phản ánh tên bảng mới
4. THE Database Schema SHALL đảm bảo tên bảng tiếng Việt không chứa dấu và tuân thủ quy tắc đặt tên MySQL

### Requirement 2

**User Story:** Là một developer, tôi muốn tất cả tên cột được chuyển sang tiếng Việt, để code Laravel models và queries dễ hiểu hơn

#### Acceptance Criteria

1. THE Database Schema SHALL chuyển đổi tất cả tên cột từ tiếng Anh sang tiếng Việt với quy tắc snake_case
2. THE Database Schema SHALL giữ nguyên các cột timestamp của Laravel (created_at, updated_at) để tương thích với framework
3. THE Database Schema SHALL giữ nguyên các cột authentication của Laravel (email_verified_at, remember_token, password) để tương thích với hệ thống auth
4. THE Database Schema SHALL cập nhật tất cả references trong foreign keys, indexes và constraints để phản ánh tên cột mới

### Requirement 3

**User Story:** Là một developer, tôi muốn các giá trị ENUM được chuyển sang tiếng Việt, để dữ liệu trong database dễ đọc và hiểu

#### Acceptance Criteria

1. THE Database Schema SHALL chuyển đổi tất cả giá trị ENUM từ tiếng Anh sang tiếng Việt không dấu
2. THE Database Schema SHALL sử dụng underscore thay vì space cho giá trị ENUM nhiều từ
3. THE Database Schema SHALL đảm bảo các giá trị ENUM mới ngắn gọn và rõ ràng
4. THE Database Schema SHALL giữ nguyên logic nghiệp vụ của các giá trị ENUM

### Requirement 4

**User Story:** Là một developer, tôi muốn các comments và mô tả được chuyển sang tiếng Việt, để documentation rõ ràng hơn

#### Acceptance Criteria

1. THE Database Schema SHALL chuyển đổi tất cả comments từ tiếng Anh sang tiếng Việt
2. THE Database Schema SHALL thêm comments tiếng Việt cho các bảng chưa có mô tả
3. THE Database Schema SHALL giữ nguyên ý nghĩa và thông tin kỹ thuật trong comments
4. THE Database Schema SHALL sử dụng tiếng Việt có dấu trong comments để dễ đọc

### Requirement 5

**User Story:** Là một developer, tôi muốn file SQL mới được tổ chức rõ ràng, để dễ review và deploy

#### Acceptance Criteria

1. THE Database Schema SHALL tạo file database mới với tên rõ ràng (ví dụ: database_vietnamese.sql)
2. THE Database Schema SHALL giữ nguyên thứ tự tạo bảng để đảm bảo foreign keys không bị lỗi
3. THE Database Schema SHALL giữ nguyên tất cả indexes và constraints
4. THE Database Schema SHALL bao gồm header comments giải thích về việc chuyển đổi
5. THE Database Schema SHALL đảm bảo file SQL có thể chạy thành công mà không có lỗi syntax
