<div id="contact-float" style="position: fixed; right: 18px; bottom: 18px; z-index: 1200;">
    <button id="contact-float-btn" type="button" aria-label="Tư vấn ngay" style="
        background: #ef233c;
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 12px 18px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        box-shadow: 0 12px 30px rgba(239, 35, 60, 0.35);
        cursor: pointer;
    ">
        <i class="fa-regular fa-comments"></i>
        <span>Tư vấn ngay</span>
    </button>
</div>

<div id="contact-float-modal" style="
    position: fixed; inset: 0; background: rgba(0,0,0,0.4);
    display: none; align-items: center; justify-content: center; z-index: 1300;
">
    <div style="background: #fff; border-radius: 18px; max-width: 520px; width: 92%; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
        <div style="padding: 16px 18px; border-bottom: 1px solid #f1f5f9; display:flex; align-items:center; justify-content: space-between;">
            <h5 style="margin:0; font-weight: 800;">Gửi yêu cầu tư vấn</h5>
            <button type="button" id="contact-float-close" aria-label="Đóng" style="background:none; border:none; font-size: 22px; cursor:pointer; color:#475569;">&times;</button>
        </div>
        <form id="contact-float-form" action="{{ route('user.contact.submit') }}" method="POST" style="padding: 16px 18px 18px;">
            @csrf
            <div class="mb-2">
                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="HoTen" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="Email" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="SDT" class="form-control">
            </div>
            <div class="mb-2">
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="TieuDe" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                <textarea name="NoiDung" rows="3" class="form-control" required></textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="button" id="contact-float-cancel" class="btn btn-outline-secondary">Hủy</button>
                <button type="submit" class="btn btn-danger" id="contact-float-submit">Gửi</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const btn = document.getElementById('contact-float-btn');
    const modal = document.getElementById('contact-float-modal');
    const closeBtn = document.getElementById('contact-float-close');
    const cancelBtn = document.getElementById('contact-float-cancel');
    const form = document.getElementById('contact-float-form');
    const submitBtn = document.getElementById('contact-float-submit');

    const toggleModal = (show) => {
        modal.style.display = show ? 'flex' : 'none';
        document.body.style.overflow = show ? 'hidden' : 'auto';
    };

    [btn, closeBtn, cancelBtn, modal].forEach(el => {
        if (!el) return;
    });

    if (btn) btn.addEventListener('click', () => toggleModal(true));
    if (closeBtn) closeBtn.addEventListener('click', () => toggleModal(false));
    if (cancelBtn) cancelBtn.addEventListener('click', () => toggleModal(false));
    if (modal) modal.addEventListener('click', (e) => { if (e.target === modal) toggleModal(false); });

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang gửi...';
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData
            }).then(res => {
                if (res.status === 422) {
                    return res.json().then(data => {
                        const errors = Object.values(data.errors || {}).flat().join(', ');
                        window.AppAlert?.show(errors || 'Vui lòng kiểm tra lại thông tin', { type: 'error' });
                        throw new Error('validation');
                    });
                }
                if (!res.ok) throw new Error('network');
                return res.json();
            }).then(() => {
                window.AppAlert?.show('Gửi yêu cầu thành công! Chúng tôi sẽ liên hệ sớm.', { type: 'success' });
                form.reset();
                toggleModal(false);
            }).catch(() => {
                window.AppAlert?.show('Không thể gửi yêu cầu, thử lại sau.', { type: 'error' });
            }).finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Gửi';
            });
        });
    }
})();
</script>
@endpush
