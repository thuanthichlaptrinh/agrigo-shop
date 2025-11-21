<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Không có quyền truy cập</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
        }

        .error-icon {
            font-size: 120px;
            color: #f44336;
            margin-bottom: 20px;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }

        h1 {
            font-size: 48px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .error-code {
            font-size: 24px;
            color: #666;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .message {
            font-size: 18px;
            color: #777;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 36px;
            }

            .error-code {
                font-size: 20px;
            }

            .message {
                font-size: 16px;
            }

            .error-icon {
                font-size: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">🚫</div>
        <h1>Truy cập bị từ chối</h1>
        <div class="error-code">403 - Unauthorized</div>
        <p class="message">
            Xin lỗi, bạn không có quyền truy cập vào khu vực này.<br>
            Khu vực quản trị chỉ dành cho Admin và Quản lý.
        </p>
        <div class="buttons">
            <a href="{{ route('user.home') }}" class="btn btn-primary">
                🏠 Về Trang Chủ
            </a>
            <a href="{{ route('login') }}" class="btn btn-secondary">
                🔑 Đăng Nhập
            </a>
        </div>
    </div>
</body>
</html>
