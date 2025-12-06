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
        
        .submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
            font-size: 12px;
            margin-top: 3px;
            display: block;
        }
        
        .input-error {
            border-color: #dc2626 !important;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <form id="registerForm" class="form mx-auto" style="margin-top: 160px">
        <p class="title">
            Đăng ký tài khoản
            <a href="{{ route('user.home') }}" class="text-dark p-3" style="position: relative; top: -40px; right: -340px">
                <img src="{{ asset('template/Assets/Icon/close.png') }}" style="width: 14px" alt="" />
            </a>
        </p>
        <p class="message">Vui lòng nhập đủ thông tin để đăng ký.</p>

        <div id="errorContainer"></div>

        <div class="flex">
            <label style="margin-right: 10px">
                <input required placeholder="" type="text" name="TenNguoiDung" id="TenNguoiDung" class="input" />
                <span>Họ và tên</span>
                <small class="field-error" id="TenNguoiDungError"></small>
            </label>

            <label>
                <input required placeholder="" type="email" name="Email" id="Email" class="input" />
                <span>Email</span>
                <small class="field-error" id="EmailError"></small>
            </label>
        </div>

        <div class="flex">
            <label style="margin-right: 10px">
                <input placeholder="" type="tel" name="SDT" id="SDT" class="input" />
                <span>Số điện thoại</span>
                <small class="field-error" id="SDTError"></small>
            </label>

            <label>
                <input placeholder="" type="text" name="DiaChi" id="DiaChi" class="input" />
                <span>Địa chỉ</span>
                <small class="field-error" id="DiaChiError"></small>
            </label>
        </div>

        <div class="flex">
            <label style="margin-right: 10px">
                <input placeholder="" type="date" name="NgaySinh" id="NgaySinh" class="input" />
                <span>Ngày sinh</span>
                <small class="field-error" id="NgaySinhError"></small>
            </label>

            <label>
                <select name="GioiTinh" id="GioiTinh" class="input">
                    <option value="">Chọn giới tính</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
                <span>Giới tính</span>
                <small class="field-error" id="GioiTinhError"></small>
            </label>
        </div>

        <div class="flex pr-10-t">
            <label style="margin-right: 10px; position: relative;">
                <input required placeholder="" type="password" name="MatKhau" id="MatKhau" class="input" style="padding-right: 35px;" />
                <span>Mật khẩu</span>
                <span class="password-toggle" onclick="togglePassword('MatKhau', this)">
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
                <small class="field-error" id="MatKhauError"></small>
            </label>

            <label style="position: relative;">
                <input required placeholder="" type="password" name="MatKhau_confirmation" id="MatKhau_confirmation" class="input" style="padding-right: 35px;" />
                <span>Nhập lại Mật khẩu</span>
                <span class="password-toggle" onclick="togglePassword('MatKhau_confirmation', this)">
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
                <small class="field-error" id="MatKhau_confirmationError"></small>
            </label>
        </div>

        <button type="submit" id="submitBtn" class="submit mt-3">
            <span class="btn-text">Đăng ký</span>
            <span class="btn-loader d-none">
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Đang xử lý...
            </span>
        </button>
        <p class="signin">
            <span>Bạn đã có tài khoản?</span>
            <a href="{{ route('login') }}">Đăng nhập</a>
        </p>
    </form>

    <script>
        const API_BASE = '/api/v1';

        // Toggle password visibility
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
            document.getElementById('errorContainer').innerHTML = '';
        }

        // Show field error
        function showFieldError(field, message) {
            const input = document.getElementById(field);
            const error = document.getElementById(field + 'Error');
            if (input) input.classList.add('input-error');
            if (error) error.textContent = message;
        }

        // Handle form submission
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
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
                TenNguoiDung: document.getElementById('TenNguoiDung').value,
                Email: document.getElementById('Email').value,
                SDT: document.getElementById('SDT').value,
                DiaChi: document.getElementById('DiaChi').value,
                NgaySinh: document.getElementById('NgaySinh').value,
                GioiTinh: document.getElementById('GioiTinh').value,
                MatKhau: document.getElementById('MatKhau').value,
                MatKhau_confirmation: document.getElementById('MatKhau_confirmation').value
            };

            try {
                const response = await fetch(`${API_BASE}/auth/register`, {
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
                    // Store token if returned
                    if (data.data && data.data.token) {
                        localStorage.setItem('jwt_token', data.data.token);
                        localStorage.setItem('user', JSON.stringify(data.data.user));
                    }
                    
                    showToast('success', 'Thành công!', data.message);
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = data.data?.redirect_url || '/login';
                    }, 1500);
                } else {
                    // Show field errors if any
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const messages = data.errors[field];
                            showFieldError(field, Array.isArray(messages) ? messages[0] : messages);
                        });
                    }
                    showToast('error', 'Đăng ký thất bại', data.message);
                }
            } catch (error) {
                showToast('error', 'Lỗi kết nối', 'Không thể kết nối đến máy chủ');
                console.error('Register error:', error);
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                btnLoader.classList.add('d-none');
            }
        });

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
