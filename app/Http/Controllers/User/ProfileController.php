<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\HoatDongNguoiDung;
use App\Models\ThongBao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    protected array $sections = ['info', 'orders', 'wishlist', 'notifications', 'password'];
    protected array $orderFilterLabels = [
        'all' => 'Tất cả',
        'new' => 'Đơn vừa đặt',
        'shipping' => 'Chờ giao hàng',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];
    protected array $orderStatusMapping = [
        'new' => ['Chờ xác nhận', 'Đơn vừa đặt', 'Đã xác nhận'],
        'shipping' => ['Chờ giao hàng', 'Đang giao hàng', 'Đang giao'],
        'completed' => ['Hoàn thành', 'Đã giao'],
        'cancelled' => ['Đã hủy', 'Đã hủy đơn'],
    ];

    public function index(Request $request)
    {
        $user = auth_user();
        if (!$user) {
            return redirect()->route('login');
        }

        $activeSection = $this->resolveSection($request->query('section', 'info'));
        $orderStatusKey = $request->query('order_status', 'all');
        if (!array_key_exists($orderStatusKey, $this->orderFilterLabels)) {
            $orderStatusKey = 'all';
        }
        $orderSearch = trim((string) $request->query('order_search', ''));

        $orders = $this->getOrdersForUser($orderStatusKey, $orderSearch);
        $wishlistItems = $this->getWishlistItemsForUser();
        $notifications = $this->getNotificationsForUser();

        $stats = [
            'orders' => $this->countOrdersForUser(),
            'wishlist' => $this->countWishlistItemsForUser(),
            'notifications' => $this->countNotificationsForUser(),
            'unreadNotifications' => $this->countNotificationsForUser(true),
        ];

        $orderFilterLabels = $this->orderFilterLabels;

        return view('user.profile', compact(
            'activeSection',
            'orders',
            'wishlistItems',
            'notifications',
            'stats',
            'orderStatusKey',
            'orderSearch',
            'orderFilterLabels'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth_user();
        if (!$user) {
            return redirect()->route('login');
        }

        $data = $request->validateWithBag('profileUpdate', [
            'TenNguoiDung' => ['required', 'string', 'max:255'],
            'SDT' => ['nullable', 'string', 'max:20'],
            'NgaySinh' => ['nullable', 'date'],
            'GioiTinh' => ['nullable', 'in:Nam,Nữ,Khác'],
            'DiaChi' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ], [
            'TenNguoiDung.required' => 'Vui lòng nhập họ tên',
            'TenNguoiDung.max' => 'Họ tên quá dài',
            'SDT.max' => 'Số điện thoại không hợp lệ',
            'NgaySinh.date' => 'Ngày sinh không hợp lệ',
            'GioiTinh.in' => 'Giới tính không hợp lệ',
            'avatar.image' => 'Vui lòng chọn đúng định dạng ảnh',
            'avatar.max' => 'Ảnh có dung lượng quá lớn (tối đa 2MB)'
        ]);

        $user->TenNguoiDung = $data['TenNguoiDung'];
        $user->SDT = $data['SDT'] ?? null;
        $user->NgaySinh = $data['NgaySinh'] ?? null;
        $user->GioiTinh = $data['GioiTinh'] ?? null;
        $user->DiaChi = $data['DiaChi'] ?? null;

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->HinhAnh && str_starts_with($user->HinhAnh, 'storage/avatars/')) {
                $oldPath = str_replace('storage/', '', $user->HinhAnh);
                Storage::disk('public')->delete($oldPath);
            }
            
            $user->HinhAnh = $this->storeAvatar($request->file('avatar'));
        }

        $user->save();

        return redirect()
            ->route('user.profile', ['section' => 'info'])
            ->with('success', 'Cập nhật thông tin thành công.');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $user = auth_user();
        if (!$user) {
            return redirect()->route('login');
        }

        $data = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'string', 'min:6'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'current_password.min' => 'Mật khẩu hiện tại phải có ít nhất 6 ký tự',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp',
        ]);

        if (!Hash::check($data['current_password'], $user->MatKhau)) {
            throw ValidationException::withMessages([
                'current_password' => ['Mật khẩu hiện tại không chính xác.'],
            ])->errorBag('passwordUpdate');
        }

        $user->MatKhau = Hash::make($data['new_password']);
        $user->save();

        return redirect()
            ->route('user.profile', ['section' => 'password'])
            ->with('success', 'Đổi mật khẩu thành công.');
    }

    public function markNotificationRead(ThongBao $notification): RedirectResponse
    {
        $user = auth_user();
        if ($user && $notification->IDNguoiDung === $user->ID) {
            $notification->markAsRead();
        }

        return redirect()->route('user.profile', ['section' => 'notifications']);
    }

    public function markAllNotificationsRead(): RedirectResponse
    {
        $user = auth_user();
        if ($user) {
            ThongBao::where('IDNguoiDung', $user->ID)
                ->where('DaXem', false)
                ->update(['DaXem' => true]);
        }

        return redirect()->route('user.profile', ['section' => 'notifications']);
    }

    public function showOrder(DonHang $order)
    {
        $user = auth_user();
        if (!$user || $order->IDNguoiDung !== $user->ID) {
            abort(403);
        }

        $order->loadMissing(['chiTiet.sanPham', 'voucher']);

        $orderSummary = [
            'code' => $order->MaDonHang ?? sprintf('DH%06d', $order->ID),
            'status' => $order->TrangThai ?? 'Chờ xử lý',
            'date' => optional($order->NgayDat)->format('d/m/Y H:i'),
            'address' => $order->DiaChi,
            'recipient' => $order->TenNguoiNhan,
            'phone' => $order->SDT,
            'note' => $order->GhiChu,
            'payment' => $order->PhuongThucTT ?? 'Thanh toán khi nhận hàng',
            'shipping_fee' => (float) ($order->PhiVanChuyen ?? 0),
            'voucher_discount' => (float) ($order->GiamVoucher ?? 0),
            'total' => (float) ($order->TongThanhToan ?? $order->getTongTienHang()),
            'items' => $order->chiTiet->map(function ($detail) {
                $product = $detail->sanPham;
                return [
                    'name' => $detail->TenSanPham ?? optional($product)->TenSanPham ?? 'Sản phẩm',
                    'quantity' => $detail->SoLuong,
                    'price' => (float) $detail->DonGia,
                    'subtotal' => (float) $detail->ThanhTien,
                    'image' => optional($product)->HinhAnh,
                ];
            }),
            'can_cancel' => $order->canCancel(),
        ];

        return view('user.orders.show', [
            'order' => $order,
            'orderSummary' => $orderSummary,
        ]);
    }

    public function cancelOrder(Request $request, DonHang $order): RedirectResponse
    {
        $user = auth_user();
        if (!$user || $order->IDNguoiDung !== $user->ID) {
            abort(403);
        }

        if (!$order->canCancel()) {
            return back()->with('warning', 'Đơn hàng không thể hủy ở trạng thái hiện tại.');
        }

        $request->validate([
            'cancel_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $order->TrangThai = 'Đã hủy';
        if ($request->filled('cancel_reason')) {
            $order->GhiChu = trim($request->cancel_reason);
        }
        $order->save();
        $order->restoreStock();

        return redirect()->route('user.profile', [
            'section' => 'orders',
        ])->with('success', 'Hủy đơn hàng thành công.');
    }

    protected function resolveSection(string $section): string
    {
        return in_array($section, $this->sections, true) ? $section : 'info';
    }

    protected function getOrdersForUser(string $filter = 'all', string $search = ''): Collection
    {
        $user = auth_user();
        if (!$user) {
            return collect();
        }

        $query = DonHang::with(['chiTiet.sanPham'])
            ->where('IDNguoiDung', $user->ID);

        $statuses = $this->resolveStatusesForFilter($filter);
        if (!empty($statuses)) {
            $query->whereIn('TrangThai', $statuses);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('MaDonHang', 'like', "%{$search}%")
                    ->orWhereHas('chiTiet', function ($detailQuery) use ($search) {
                        $detailQuery->where('TenSanPham', 'like', "%{$search}%");
                    });
            });
        }

        return $query
            ->latest('NgayDat')
            ->get()
            ->map(function (DonHang $order) {
                return [
                    'id' => $order->ID,
                    'code' => $order->MaDonHang ?? sprintf('DH%06d', $order->ID),
                    'date' => optional($order->NgayDat)->format('d/m/Y H:i'),
                    'total' => (float) ($order->TongThanhToan ?? $order->getTongTienHang()),
                    'status' => $order->TrangThai ?? 'Chờ xử lý',
                    'status_color' => $this->statusColor($order->TrangThai),
                    'items' => $order->chiTiet->map(function ($detail) {
                        $product = $detail->sanPham;
                        return [
                            'name' => $detail->TenSanPham ?? optional($product)->TenSanPham ?? 'Sản phẩm',
                            'quantity' => $detail->SoLuong,
                            'price' => (float) $detail->DonGia,
                            'image' => optional($product)->HinhAnh,
                        ];
                    })->values()->all(),
                    'can_cancel' => $order->canCancel(),
                ];
            });
    }

    protected function getWishlistItemsForUser(): Collection
    {
        $user = auth_user();
        if (!$user) {
            return collect();
        }

        return HoatDongNguoiDung::with('sanPham')
            ->where('IDNguoiDung', $user->ID)
            ->where('Loai', 'Yêu thích')
            ->latest('Ngay')
            ->take(20)
            ->get()
            ->filter(fn ($activity) => $activity->sanPham && ($activity->sanPham->TrangThai ?? true))
            ->map(function ($activity) {
                $product = $activity->sanPham;
                return [
                    'id' => $product->ID,
                    'name' => $product->TenSanPham,
                    'price' => (float) $product->Gia,
                    'unit' => $product->DonViTinh ?? 'Gói',
                    'image' => $product->HinhAnh,
                    'added_at' => optional($activity->Ngay)->format('d/m/Y'),
                ];
            })
            ->values();
    }

    protected function resolveStatusesForFilter(string $filter): array
    {
        return $this->orderStatusMapping[$filter] ?? [];
    }

    protected function getNotificationsForUser(): Collection
    {
        $user = auth_user();
        if (!$user) {
            return collect();
        }

        return ThongBao::where('IDNguoiDung', $user->ID)
            ->latest('NgayTao')
            ->take(15)
            ->get()
            ->map(function (ThongBao $notification) {
                return [
                    'id' => $notification->ID,
                    'title' => $notification->TieuDe,
                    'content' => $notification->NoiDung,
                    'type' => $notification->Loai ?? 'Thông báo',
                    'is_read' => (bool) $notification->DaXem,
                    'created_at' => optional($notification->NgayTao)->format('d/m/Y H:i'),
                    'relative_time' => optional($notification->NgayTao)->diffForHumans(),
                    'link' => $notification->LinkLienKet,
                ];
            });
    }

    protected function statusColor(?string $status): string
    {
        $normalized = Str::lower($status ?? '');

        return match (true) {
            Str::contains($normalized, 'hoàn') || Str::contains($normalized, 'giao') => 'success',
            Str::contains($normalized, 'chờ') || Str::contains($normalized, 'xác nhận') => 'warning',
            Str::contains($normalized, 'hủy') => 'danger',
            default => 'secondary',
        };
    }

    protected function storeAvatar(UploadedFile $file): string
    {
        // Tạo tên file duy nhất
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        
        // Lưu file vào storage/app/public/avatars
        $path = $file->storeAs('avatars', $filename, 'public');
        
        // Trả về đường dẫn để lưu vào database: storage/avatars/filename.jpg
        return 'storage/' . $path;
    }

    protected function countOrdersForUser(): int
    {
        $user = auth_user();
        return $user ? DonHang::where('IDNguoiDung', $user->ID)->count() : 0;
    }

    protected function countWishlistItemsForUser(): int
    {
        $user = auth_user();
        return $user ? HoatDongNguoiDung::where('IDNguoiDung', $user->ID)->where('Loai', 'Yêu thích')->count() : 0;
    }

    protected function countNotificationsForUser(bool $onlyUnread = false): int
    {
        $user = auth_user();
        if (!$user) {
            return 0;
        }

        $query = ThongBao::where('IDNguoiDung', $user->ID);
        if ($onlyUnread) {
            $query->where('DaXem', false);
        }
        return $query->count();
    }
}
