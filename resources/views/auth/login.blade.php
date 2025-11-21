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
    <title>Đăng nhập - Organic Shop</title>

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
                <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600">Be Verified</p>
                <small class="text-white text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace">
                    Join experienced Designers on this platform.
                </small>
            </div>

            <!-- Right Box -->
            <div class="col-md-6 right-box">
                <a href="{{ route('user.home') }}" class="text-dark p-3 close" style="position: relative; top: -40px; right: -365px">
                    <img src="{{ asset('template/Assets/Icon/close.png') }}" style="width: 14px" alt="" />
                </a>
                <form class="row align-items-center" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="header-text mb-4">
                        <h2>Xin chào !</h2>
                        <p>Chúng tôi rất vui khi bạn trở lại.</p>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email" required />
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-1">
                        <input type="password" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Mật khẩu" required />
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-5 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="formCheck" />
                            <label for="formCheck" class="form-check-label text-secondary"><small>Ghi nhớ</small></label>
                        </div>
                        <div class="forgot">
                            <small><a href="{{ route('password.request') }}">Quên mật khẩu?</a></small>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <button
                            type="submit"
                            class="btn btn-xanh text-white btn-lg w-100 fs-6"
                            style="background: radial-gradient(159.85% 367.97% at 150% 123.85%, #ffe147 0, #65ae17 38.76%, #469c4b 59.65%, #00713b 100%)"
                        >
                            Đăng nhập
                        </button>
                    </div>
                    <div class="input-group mb-3">
                        <button type="button" class="btn btn-lg btn-light w-100 fs-6">
                            <img src="{{ asset('template/images/google.png') }}" style="width: 20px" class="me-2" />
                            <small>Đăng nhập bằng Google</small>
                        </button>
                    </div>
                    <div class="row">
                        <small>
                            Bạn chưa có tài khoản?
                            <a href="{{ route('register') }}">Đăng ký</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
