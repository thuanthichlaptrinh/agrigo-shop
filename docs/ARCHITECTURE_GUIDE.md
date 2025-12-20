# 🏗️ HƯỚNG DẪN KIẾN TRÚC DỰ ÁN

> Tài liệu mô tả cấu trúc Services, Repositories, Form Requests và Enums

---

## 📂 CẤU TRÚC THƯ MỤC MỚI

```
app/
├── Enums/                          ← PHP 8.1 Enums
│   ├── OrderStatus.php             # Trạng thái đơn hàng
│   ├── PaymentMethod.php           # Phương thức thanh toán
│   └── UserRole.php                # Vai trò người dùng
│
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Api/
│   │   └── User/
│   │
│   ├── Middleware/
│   │
│   └── Requests/                   ← Form Request Validation
│       ├── Admin/
│       │   ├── StoreProductRequest.php
│       │   ├── UpdateProductRequest.php
│       │   ├── StoreOrderRequest.php
│       │   └── UpdateOrderRequest.php
│       ├── Auth/
│       │   ├── LoginRequest.php
│       │   └── RegisterRequest.php
│       └── User/
│           └── CheckoutRequest.php
│
├── Repositories/                   ← Data Access Layer
│   ├── Contracts/                  # Interfaces
│   │   ├── BaseRepositoryInterface.php
│   │   ├── ProductRepositoryInterface.php
│   │   └── OrderRepositoryInterface.php
│   ├── BaseRepository.php          # Abstract base class
│   ├── ProductRepository.php
│   ├── OrderRepository.php
│   └── UserRepository.php
│
├── Services/                       ← Business Logic Layer
│   ├── ProductService.php
│   ├── OrderService.php
│   ├── AuthService.php
│   └── VoucherService.php
│
├── Providers/
│   └── RepositoryServiceProvider.php
│
└── Support/                        ← Helpers & Traits
    ├── Auth/
    ├── Cart/
    ├── Logging/
    └── Traits/
```

---

## 🔄 LUỒNG XỬ LÝ REQUEST

```
Request
   │
   ▼
┌─────────────────┐
│   Form Request  │  ← Validation
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   Controller    │  ← Điều phối
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│    Service      │  ← Business Logic
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   Repository    │  ← Data Access
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│     Model       │  ← Eloquent ORM
└────────┬────────┘
         │
         ▼
    Database
```

---

## 📦 REPOSITORIES

### BaseRepository

Cung cấp các method CRUD cơ bản:

```php
// Lấy tất cả records
$repository->all();

// Tìm theo ID
$repository->find($id);
$repository->findOrFail($id);

// CRUD
$repository->create($data);
$repository->update($id, $data);
$repository->delete($id);

// Query builder
$repository->with(['relation'])->find($id);
$repository->where('field', 'value')->all();
$repository->orderBy('created_at', 'desc')->paginate(10);
```

### ProductRepository

```php
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepository
    ) {}

    public function index()
    {
        // Lấy sản phẩm với filters
        $products = $this->productRepository->getWithFilters([
            'search' => 'rau',
            'category' => 1,
            'status' => 1,
            'price_min' => 10000,
            'sort_by' => 'Gia',
        ], perPage: 20);

        // Lấy thống kê
        $stats = $this->productRepository->getProductStats();

        // Lấy sản phẩm nổi bật
        $featured = $this->productRepository->getFeaturedProducts(8);
    }
}
```

### OrderRepository

```php
use App\Repositories\OrderRepository;

// Lấy đơn hàng của user
$orders = $orderRepository->getByUser($userId);

// Lấy theo trạng thái
$pending = $orderRepository->getByStatus('Chờ xác nhận');

// Thống kê
$stats = $orderRepository->getOrderStats();
$revenue = $orderRepository->getRevenueByStatus(['Đã giao']);
```

---

## 🛠️ SERVICES

### ProductService

```php
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create(
            data: $request->validated(),
            image: $request->file('HinhAnh'),
            gallery: $request->file('gallery') ?? []
        );

        return redirect()->route('admin.products.index');
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $this->productService->update(
            id: $id,
            data: $request->validated(),
            image: $request->file('HinhAnh'),
            gallery: $request->file('gallery') ?? [],
            deleteImages: $request->input('delete_images', [])
        );

        return redirect()->route('admin.products.index');
    }
}
```

### OrderService

```php
use App\Services\OrderService;

// Tạo đơn hàng
$order = $orderService->create(
    data: [
        'IDNguoiDung' => $userId,
        'TenNguoiNhan' => 'Nguyễn Văn A',
        'SDT' => '0912345678',
        'DiaChi' => '123 ABC',
        'PhuongThucTT' => 'COD',
        'PhiVanChuyen' => 20000,
    ],
    products: [
        ['IDSanPham' => 1, 'SoLuong' => 2],
        ['IDSanPham' => 3, 'SoLuong' => 1],
    ]
);

// Cập nhật trạng thái
$orderService->updateStatus($orderId, 'Đang giao');

// Hủy đơn hàng
$orderService->cancel($orderId, 'Khách yêu cầu hủy');
```

### AuthService

```php
use App\Services\AuthService;

// Đăng nhập
$result = $authService->login($email, $password);
if ($result['success']) {
    return redirect()->route($result['redirect']);
}
return back()->withErrors([$result['error'] => $result['message']]);

// Đăng ký
$result = $authService->register($data);

// Đăng xuất
$authService->logout();
```

---

## 📝 FORM REQUESTS

### Sử dụng trong Controller

```php
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;

class ProductController extends Controller
{
    // Validation tự động chạy trước khi vào method
    public function store(StoreProductRequest $request)
    {
        // $request->validated() trả về data đã validate
        $data = $request->validated();
        
        // Xử lý...
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $data = $request->validated();
        // Xử lý...
    }
}
```

### Tạo Form Request mới

```bash
php artisan make:request Admin/StoreVoucherRequest
```

```php
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MaVoucher' => ['required', 'string', 'unique:Voucher,MaVoucher'],
            'GiaTri' => ['required', 'numeric', 'min:0'],
            // ...
        ];
    }

    public function messages(): array
    {
        return [
            'MaVoucher.required' => 'Vui lòng nhập mã voucher',
            // ...
        ];
    }
}
```

---

## 🎯 ENUMS

### OrderStatus

```php
use App\Enums\OrderStatus;

// Lấy giá trị
$status = OrderStatus::PENDING;
echo $status->value;  // "Chờ xác nhận"
echo $status->label(); // "Chờ xác nhận"
echo $status->color(); // "warning"
echo $status->icon();  // "ri-time-line"

// Kiểm tra chuyển trạng thái hợp lệ
$canTransition = OrderStatus::PENDING->canTransitionTo(OrderStatus::SHIPPING);

// Lấy tất cả options cho dropdown
$options = OrderStatus::options();
// ['Chờ xác nhận' => 'Chờ xác nhận', 'Đã xác nhận' => 'Đã xác nhận', ...]
```

### PaymentMethod

```php
use App\Enums\PaymentMethod;

$method = PaymentMethod::COD;
echo $method->label(); // "Thanh toán khi nhận hàng"

// Convert từ input
$method = PaymentMethod::fromInput('vnpay'); // PaymentMethod::VNPAY
```

### UserRole

```php
use App\Enums\UserRole;

$role = UserRole::ADMIN;
echo $role->isAdmin();        // true
echo $role->canAccessAdmin(); // true

$permissions = $role->permissions(); // ['*']
```

---

## 🔧 CÁCH SỬ DỤNG

### 1. Trong Controller mới

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(Request $request)
    {
        $products = $this->productService->getProducts(
            filters: $request->all(),
            perPage: $request->input('per_page', 10)
        );

        $stats = $this->productService->getProductStats();

        return view('admin.products.index', compact('products', 'stats'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->create(
            data: $request->validated(),
            image: $request->file('HinhAnh'),
            gallery: $request->file('gallery') ?? []
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }
}
```

### 2. Tạo Service mới

```php
<?php

namespace App\Services;

use App\Repositories\VoucherRepository;

class VoucherService
{
    public function __construct(
        protected VoucherRepository $voucherRepository
    ) {}

    public function create(array $data): Voucher
    {
        // Business logic here
        return $this->voucherRepository->create($data);
    }
}
```

### 3. Tạo Repository mới

```php
<?php

namespace App\Repositories;

use App\Models\Voucher;

class VoucherRepository extends BaseRepository
{
    protected function model(): string
    {
        return Voucher::class;
    }

    // Custom methods
    public function getActiveVouchers()
    {
        return $this->query
            ->where('NgayKetThuc', '>=', now())
            ->get();
    }
}
```

---

## ✅ CHECKLIST KHI TẠO FEATURE MỚI

1. [ ] Tạo Form Request trong `app/Http/Requests/`
2. [ ] Tạo Repository trong `app/Repositories/`
3. [ ] Tạo Service trong `app/Services/`
4. [ ] Đăng ký Repository trong `RepositoryServiceProvider`
5. [ ] Inject Service vào Controller
6. [ ] Sử dụng Form Request trong Controller method
7. [ ] Tạo Enum nếu cần (status, type, ...)

---

> 📅 Tài liệu được tạo: 2025-12-20
