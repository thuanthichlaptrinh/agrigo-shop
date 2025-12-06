<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truy cập bị từ chối</title>
      <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome 6 Free CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <!-- Boxicons CDN (Backup) -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="/template/admin/style.css" />
    <link rel="stylesheet" href="/template/admin/products.css" />
    <style>
        .unauth-wrapper {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            background: linear-gradient(145deg, #eef2ff 0%, #f8fafc 100%);
        }
        .unauth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
            padding: 36px 42px;
            max-width: 680px;
            width: 100%;
            text-align: center;
        }
        .unauth-icon {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 44px;
            margin-bottom: 18px;
        }
        .unauth-title { font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 10px; }
        .unauth-code { color: #475569; font-weight: 700; margin-bottom: 14px; }
        .unauth-msg { color: #64748b; line-height: 1.6; margin-bottom: 24px; }
        .unauth-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-ghost, .btn-solid {
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-solid { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; box-shadow: 0 12px 24px rgba(99, 102, 241, 0.25); }
        .btn-solid:hover { transform: translateY(-1px); box-shadow: 0 16px 28px rgba(99, 102, 241, 0.28); }
        .btn-ghost { background: #eef2ff; color: #4338ca; border: 1px solid rgba(67, 56, 202, 0.15); }
        .btn-ghost:hover { background: #e0e7ff; }
        @media (max-width: 640px) {
            .unauth-card { padding: 28px; }
            .unauth-title { font-size: 22px; }
        }
    </style>
</head>
<body>
    @include('admin.partials.sidebar')
    <section id="content">
        @include('admin.partials.navbar')
        <main style="margin-top: 64px;">
            <div class="unauth-wrapper">
                <div class="unauth-card">
                    <div class="unauth-icon"><i class="fa-solid fa-ban"></i></div>
                    <div class="unauth-title">Truy cập bị từ chối</div>
                    <div class="unauth-code">403 - Unauthorized</div>
                    <p class="unauth-msg">Xin lỗi, bạn không có quyền truy cập chức năng này. Nếu bạn cần thực hiện thao tác, vui lòng liên hệ quản trị viên.</p>
                    <div class="unauth-actions">
                        <a href="{{ route('admin.dashboard') }}" class="btn-solid"><i class="fa-solid fa-chart-line"></i> Về Dashboard</a>
                        <a href="{{ route('user.home') }}" class="btn-ghost"><i class="fa-solid fa-house"></i> Trang chủ</a>
                    </div>
                </div>
            </div>
        </main>
    </section>
</body>
</html>
