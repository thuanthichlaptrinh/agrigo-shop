<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
        crossorigin="anonymous"
    />
    <title>Đăng ký - Organic Shop</title>

    <style>
        body {
            background: #ccc;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 650px;
            background-color: #fff;
            padding: 20px;
            border-radius: 20px;
            position: relative;
        }

        .title {
            font-size: 28px;
            color: rgb(0 97 51 / 1);
            font-weight: 600;
            letter-spacing: -1px;
            position: relative;
            display: flex;
            align-items: center;
            padding-left: 30px;
        }

        .title::before,
        .title::after {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            border-radius: 50%;
            left: 0px;
            background: radial-gradient(159.85% 367.97% at 150% 123.85%, #ffe147 0, #65ae17 38.76%, #469c4b 59.65%, #00713b 100%);
        }

        .title::before {
            width: 18px;
            height: 18px;
            background: radial-gradient(159.85% 367.97% at 150% 123.85%, #ffe147 0, #65ae17 38.76%, #469c4b 59.65%, #00713b 100%);
        }

        .title::after {
            width: 18px;
            height: 18px;
            animation: pulse 1s linear infinite;
        }

        .message,
        .signin {
            color: rgba(88, 87, 87, 0.822);
            font-size: 14px;
        }

        .signin {
            text-align: center;
        }

        .signin a {
            color: rgb(0 97 51 / 1);
        }

        .signin a:hover {
            text-decoration: underline royalblue;
        }

        .flex {
            display: flex;
            width: 100%;
            gap: 6px;
        }

        .form label {
            position: relative;
        }

        .form label .input {
            width: 295px;
            padding: 14px 10px 14px 10px;
            outline: 0;
            border: 1px solid rgba(105, 105, 105, 0.397);
            border-radius: 10px;
        }

        .form label .input + span {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: grey;
            font-size: 0.9em;
            cursor: text;
            transition: all 0.3s ease;
            background-color: transparent;
            padding: 0 5px;
            pointer-events: none;
        }

        .form label .input:placeholder-shown + span {
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9em;
        }

        .form label .input:focus + span,
        .form label .input:not(:placeholder-shown) + span {
            top: 0;
            transform: translateY(-50%);
            font-size: 0.75em;
            font-weight: 600;
            color: rgb(0 97 51 / 1);
            background-color: #fff;
        }

        /* Style cho select - chỉ khi focus hoặc có giá trị được chọn */
        .form label select.input:focus + span,
        .form label select.input.has-value + span {
            top: 0;
            transform: translateY(-50%);
            font-size: 0.75em;
            font-weight: 600;
            color: rgb(0 97 51 / 1);
            background-color: #fff;
        }

        /* Style cho date input - chỉ khi focus hoặc có giá trị */
        .form label input[type="date"]:focus + span,
        .form label input[type="date"].has-value + span {
            top: 0;
            transform: translateY(-50%);
            font-size: 0.75em;
            font-weight: 600;
            color: rgb(0 97 51 / 1);
            background-color: #fff;
        }

        .submit {
            border: none;
            outline: none;
            background: radial-gradient(159.85% 367.97% at 150% 123.85%, #ffe147 0, #65ae17 38.76%, #469c4b 59.65%, #00713b 100%);
            padding: 10px;
            border-radius: 10px;
            color: #fff;
            font-size: 16px;
            transform: 0.3s ease;
        }

        .submit:hover {
            opacity: 0.8;
        }

        @keyframes pulse {
            from {
                transform: scale(0.9);
                opacity: 1;
            }

            to {
                transform: scale(1.8);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <form class="form mx-auto" style="margin-top: 160px" action="{{ route('register') }}" method="POST">
        @csrf
        <p class="title">
            Đăng ký tài khoản
            <a href="{{ route('user.home') }}" class="text-dark p-3" style="position: relative; top: -40px; right: -340px">
                <img src="{{ asset('template/Assets/Icon/close.png') }}" style="width: 14px" alt="" />
            </a>
        </p>
        <p class="message">Vui lòng nhập đủ thông tin để đăng ký.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex">
            <label style="margin-right: 10px">
                <input required placeholder="" type="text" name="TenNguoiDung" class="input" value="{{ old('TenNguoiDung') }}" />
                <span>Họ và tên</span>
            </label>

            <label>
                <input required placeholder="" type="email" name="Email" class="input" value="{{ old('Email') }}" />
                <span>Email</span>
            </label>
        </div>

        <div class="flex">
            <label style="margin-right: 10px">
                <input placeholder="" type="tel" name="SDT" class="input" value="{{ old('SDT') }}" />
                <span>Số điện thoại</span>
            </label>

            <label>
                <input placeholder="" type="text" name="DiaChi" class="input" value="{{ old('DiaChi') }}" />
                <span>Địa chỉ</span>
            </label>
        </div>

        <div class="flex">
            <label style="margin-right: 10px">
                <input placeholder="" type="date" name="NgaySinh" class="input" value="{{ old('NgaySinh') }}" />
                <span>Ngày sinh</span>
            </label>

            <label>
                <select name="GioiTinh" class="input">
                    <option value="">Chọn giới tính</option>
                    <option value="Nam" {{ old('GioiTinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('GioiTinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ old('GioiTinh') == 'Khác' ? 'selected' : '' }}>Khác</option>
                </select>
                <span>Giới tính</span>
            </label>
        </div>

        <div class="flex pr-10-t">
            <label style="margin-right: 10px">
                <input required placeholder="" type="password" name="MatKhau" class="input" />
                <span>Mật khẩu</span>
            </label>

            <label>
                <input required placeholder="" type="password" name="MatKhau_confirmation" class="input" />
                <span>Nhập lại Mật khẩu</span>
            </label>
        </div>

        <button type="submit" class="submit mt-3">Đăng ký</button>
        <p class="signin">
            <span>Bạn đã có tài khoản?</span>
            <a href="{{ route('login') }}">Đăng nhập</a>
        </p>
    </form>

    <script>
        // Xử lý floating label cho select
        document.querySelectorAll('select.input').forEach(select => {
            select.addEventListener('change', function() {
                if (this.value !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
            
            // Kiểm tra giá trị ban đầu
            if (select.value !== '') {
                select.classList.add('has-value');
            }
        });

        // Xử lý floating label cho date input
        document.querySelectorAll('input[type="date"].input').forEach(dateInput => {
            dateInput.addEventListener('change', function() {
                if (this.value !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
            
            // Kiểm tra giá trị ban đầu
            if (dateInput.value !== '') {
                dateInput.classList.add('has-value');
            }
        });
    </script>
</body>
</html>
