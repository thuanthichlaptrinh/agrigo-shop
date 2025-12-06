<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        .btn-xanh:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Toast notification styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .custom-toast {
            min-width: 300px;
            padding: 15px 20px;
            padding-right: 35px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            margin-bottom: 10px;
            animation: slideIn 0.3s ease;
            position: relative;
        }
        
        .custom-toast.success {
            border-left: 4px solid #22c55e;
        }
        
        .custom-toast.error {
            border-left: 4px solid #ef4444;
        }
        
        .custom-toast .toast-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .custom-toast.success .toast-title {
            color: #16a34a;
        }
        
        .custom-toast.error .toast-title {
            color: #dc2626;
        }
        
        .custom-toast .toast-message {
            font-size: 14px;
            color: #666;
        }
        
        .custom-toast .toast-close {
            position: absolute;
            top: 8px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            color: #999;
            cursor: pointer;
            line-height: 1;
        }
        
        .custom-toast .toast-close:hover {
            color: #333;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .field-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }
        
        .input-error {
            border-color: #dc2626 !important;
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
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <!-- Left Box -->
            <div
                class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
                style="background: radial-gradient(159.85% 367.97% at 150% 123.85%, #ffe147 0, #65ae17 38.76%, #469c4b 59.65%, #00713b 100%)"
            >
                
                <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600">Agrigo Shop</p>
                <small class="text-white text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace">
                    Agrigo Shop xin kính chào quý khách.
                </small>
            </div>

            <!-- Right Box -->
            <div class="col-md-6 right-box">
                <a href="{{ route('user.home') }}" class="text-dark p-3 close" style="position: relative; top: -40px; right: -365px">
                    <img src="{{ asset('template/Assets/Icon/close.png') }}" style="width: 14px" alt="" />
                </a>
                <form id="loginForm" class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Xin chào !</h2>
                        <p>Chúng tôi rất vui khi bạn trở lại.</p>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" id="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email" required />
                    </div>
                    <div class="field-error mb-2" id="emailError"></div>
                    
                    <div class="input-group mb-1">
                        <input type="password" name="password" id="password" class="form-control form-control-lg bg-light fs-6" placeholder="Mật khẩu" required style="border-right: none;" />
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer; border-left: none; background-color: #e8f0fd;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" id="eyeIconOpen">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash d-none" viewBox="0 0 16 16" id="eyeIconClosed">
                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                                <path d="M11.297 9.377L9.499 7.58l-1.15 1.15-1.748 1.75a2.5 2.5 0 0 0 2.5 2.5c.745 0 1.43-.3 1.947-.824z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                <path d="M1.646 2.646a.5.5 0 0 1 .708 0l12 12a.5.5 0 0 1-.708.708l-12-12a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="field-error mb-2" id="passwordError"></div>
                    
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
                            id="submitBtn"
                            class="btn btn-xanh text-white btn-lg w-100 fs-6"
                            style="background: radial-gradient(159.85% 367.97% at 150% 123.85%, #ffe147 0, #65ae17 38.76%, #469c4b 59.65%, #00713b 100%)"
                        >
                            <span class="btn-text">Đăng nhập</span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Đang xử lý...
                            </span>
                        </button>
                    </div>
                    <div class="input-group mb-3">
                        <button type="button" class="btn btn-lg btn-light w-100 fs-6 d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" class="me-2">
                                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                            </svg>
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

    <script>
        const API_BASE = '/api/v1';

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const eyeIconOpen = document.getElementById('eyeIconOpen');
            const eyeIconClosed = document.getElementById('eyeIconClosed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIconOpen.classList.add('d-none');
                eyeIconClosed.classList.remove('d-none');
            } else {
                passwordInput.type = 'password';
                eyeIconOpen.classList.remove('d-none');
                eyeIconClosed.classList.add('d-none');
            }
        });

        // Show toast notification
        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `custom-toast ${type}`;
            toast.innerHTML = `
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
                <button type="button" class="toast-close" onclick="this.parentElement.remove()">&times;</button>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'slideIn 0.3s ease reverse';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        }

        // Clear all field errors
        function clearErrors() {
            document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
            document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        }

        // Show field error
        function showFieldError(field, message) {
            const input = document.getElementById(field);
            const error = document.getElementById(field + 'Error');
            if (input) input.classList.add('input-error');
            if (error) error.textContent = message;
        }

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();

            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');

            // Show loading state
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoader.classList.remove('d-none');

            const formData = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };

            try {
                const response = await fetch(`${API_BASE}/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    // Store token
                    localStorage.setItem('jwt_token', data.data.token);
                    localStorage.setItem('user', JSON.stringify(data.data.user));
                    
                    showToast('success', 'Thành công!', data.message);
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = data.data.redirect_url || '/';
                    }, 1000);
                } else {
                    // Show field errors if any
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const messages = data.errors[field];
                            showFieldError(field, Array.isArray(messages) ? messages[0] : messages);
                        });
                    }
                    showToast('error', 'Đăng nhập thất bại', data.message);
                }
            } catch (error) {
                showToast('error', 'Lỗi kết nối', 'Không thể kết nối đến máy chủ');
                console.error('Login error:', error);
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                btnLoader.classList.add('d-none');
            }
        });
    </script>
</body>
</html>
