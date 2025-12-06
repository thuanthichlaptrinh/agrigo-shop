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
    <title>Đặt lại mật khẩu - Organic Shop</title>

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap");

        body {
            font-family: "Poppins", sans-serif;
            background: #ececec;
        }

        .box-area {
            width: 930px;
        }

        .right-box {
            padding: 40px 30px 40px 40px;
        }

        ::placeholder {
            font-size: 16px;
        }

        .rounded-4 {
            border-radius: 20px;
        }
        .rounded-5 {
            border-radius: 30px;
        }

        .btn-xanh:hover {
            opacity: 0.8;
        }

        @media only screen and (max-width: 768px) {
            .box-area {
                margin: 0 10px;
            }
            .left-box {
                height: 100px;
                overflow: hidden;
            }
            .right-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <!-- Left Box -->
            <div
                class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
                style="background: radial-gradient(159.85% 367.97% at 150% 123.85%, #ffe147 0, #65ae17 38.76%, #469c4b 59.65%, #00713b 100%)"
            >
                <div class="featured-image mb-3">
                    <img src="{{ asset('template/images/1.png') }}" class="img-fluid" style="width: 250px" />
                </div>
                <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600">Đặt lại mật khẩu</p>
                <small class="text-white text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace">
                    Tạo mật khẩu mới cho tài khoản của bạn.
                </small>
            </div>

            <!-- Right Box -->
            <div class="col-md-6 right-box">
                <a href="{{ route('login') }}" class="text-dark p-3" style="position: relative; top: -40px; right: -365px">
                    <img src="{{ asset('template/Assets/Icon/close.png') }}" style="width: 14px" alt="" />
                </a>
                <form class="row align-items-center" action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="header-text mb-4">
                        <h2>Đặt lại mật khẩu</h2>
                        <p>Nhập email và mật khẩu mới của bạn.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email" value="{{ old('email') }}" required />
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control form-control-lg bg-light fs-6" placeholder="Mật khẩu mới" required style="border-right: none;" />
                        <span class="input-group-text" onclick="togglePassword('password', this)" style="cursor: pointer; border-left: none; background-color: #e8f0fd">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye eye-open" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash eye-closed d-none" viewBox="0 0 16 16">
                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                                <path d="M11.297 9.377L9.499 7.58l-1.15 1.15-1.748 1.75a2.5 2.5 0 0 0 2.5 2.5c.745 0 1.43-.3 1.947-.824z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                <path d="M1.646 2.646a.5.5 0 0 1 .708 0l12 12a.5.5 0 0 1-.708.708l-12-12a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </span>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg bg-light fs-6" placeholder="Xác nhận mật khẩu mới" required style="border-right: none;" />
                        <span class="input-group-text bg-light" onclick="togglePassword('password_confirmation', this)" style="cursor: pointer; border-left: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye eye-open" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash eye-closed d-none" viewBox="0 0 16 16">
                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                                <path d="M11.297 9.377L9.499 7.58l-1.15 1.15-1.748 1.75a2.5 2.5 0 0 0 2.5 2.5c.745 0 1.43-.3 1.947-.824z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                <path d="M1.646 2.646a.5.5 0 0 1 .708 0l12 12a.5.5 0 0 1-.708.708l-12-12a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </span>
                    </div>

                    <div class="input-group mb-3">
                        <button
                            type="submit"
                            class="btn btn-xanh text-white btn-lg w-100 fs-6"
                            style="background: radial-gradient(159.85% 367.97% at 150% 123.85%, #ffe147 0, #65ae17 38.76%, #469c4b 59.65%, #00713b 100%)"
                        >
                            Đặt lại mật khẩu
                        </button>
                    </div>

                    <div class="row">
                        <small>
                            <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId, toggleElement) {
            const input = document.getElementById(inputId);
            const eyeOpen = toggleElement.querySelector('.eye-open');
            const eyeClosed = toggleElement.querySelector('.eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('d-none');
                eyeClosed.classList.remove('d-none');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('d-none');
                eyeClosed.classList.add('d-none');
            }
        }
    </script>
</body>
</html>
