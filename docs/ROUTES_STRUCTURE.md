# Cấu trúc Routes - Quick Reference

## 📂 Cấu trúc hiện tại

```
routes/
├── web.php          ← File chính, load tất cả routes khác
├── auth.php         ← Login, Register, Logout
├── product.php      ← Danh sách SP, Chi tiết, Tìm kiếm
├── cart.php         ← Giỏ hàng, Thanh toán
└── user.php         ← Profile (cần đăng nhập)
```

## 🔗 Luồng Routes

```
Request → web.php (Main)
            ↓
            ├─→ require auth.php (Authentication)
            ├─→ require product.php (Products)
            ├─→ require cart.php (Cart & Checkout)
            └─→ require user.php (User Profile + Auth Middleware)
```

## 📋 Routes List

### 🏠 **web.php** (1 route)

```
GET  /                  → home
```

### 🔐 **auth.php** (3 routes)

```
GET   /login           → login
POST  /login           → (process)
GET   /register        → register
POST  /register        → (process)
POST  /logout          → logout
```

### 📦 **product.php** (4 routes)

```
GET  /products          → products
GET  /product/{id}      → product-detail
GET  /search            → search
GET  /category/{slug}   → category
```

### 🛒 **cart.php** (7 routes)

```
GET     /cart                → cart
POST    /cart/add            → cart.add
POST    /cart/update         → cart.update
DELETE  /cart/remove/{id}    → cart.remove
DELETE  /cart/clear          → cart.clear
GET     /checkout            → checkout
POST    /checkout            → checkout.process
```

### 👤 **user.php** (15 routes - Cần Auth)

```
GET     /profile                        → profile
PUT     /profile                        → profile.update
POST    /profile/avatar                 → profile.avatar
GET     /orders                         → orders
GET     /orders/{id}                    → orders.show
POST    /orders/{id}/cancel             → orders.cancel
GET     /addresses                      → addresses
POST    /addresses                      → addresses.store
PUT     /addresses/{id}                 → addresses.update
DELETE  /addresses/{id}                 → addresses.destroy
GET     /wishlist                       → wishlist
POST    /wishlist/add/{productId}       → wishlist.add
DELETE  /wishlist/remove/{productId}    → wishlist.remove
GET     /notifications                  → notifications
POST    /notifications/{id}/read        → notifications.read
POST    /notifications/read-all         → notifications.readAll
GET     /change-password                → change-password
POST    /change-password                → change-password.update
```

## 🚀 Kiểm tra

```bash
# Xem tất cả routes
php artisan route:list

# Xem routes theo tên
php artisan route:list --name=product

# Xem routes theo middleware
php artisan route:list --middleware=auth
```

## ✅ Tổng số: 30 routes

-   Public routes: 15
-   Protected routes (auth): 15
