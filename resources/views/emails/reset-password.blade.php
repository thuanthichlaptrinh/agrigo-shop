<!DOCTYPE html>
<html>
<head>
    <title>Đặt lại mật khẩu</title>
</head>
<body>
    <h2>Xin chào {{ $user->TenNguoiDung }},</h2>
    <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
    <p>Vui lòng nhấp vào liên kết bên dưới để đặt lại mật khẩu:</p>
    <a href="{{ route('password.reset', $token) }}">Đặt lại mật khẩu</a>
    <p>Liên kết này sẽ hết hạn sau 60 phút.</p>
    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
    <p>Trân trọng,<br>Organic Shop</p>
</body>
</html>
