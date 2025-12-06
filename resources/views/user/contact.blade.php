@extends('user.layouts.app')

@section('title', 'Liên hệ & Hỗ trợ')

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, #e0f2fe 0%, #ecfdf3 100%);
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 18px;
    }
    .contact-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        height: 100%;
    }
    .contact-card h5 { font-weight: 700; }
    .faq-item {
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 0;
    }
    .faq-question {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        color: #0f172a;
    }
    .faq-answer { display: none; color: #4b5563; margin-top: 8px; }
    .faq-item.active .faq-answer { display: block; }
    .faq-item.active .faq-question i { transform: rotate(180deg); }
</style>
@endpush

@section('content')
<div class="row">
    @include('user.partials.sidebar')

    <div class="col-9 content-body">
        <div class="contact-hero">
            <h3 class="mb-2">Liên hệ & Hỗ trợ</h3>
            <p class="mb-0 text-muted">Gửi câu hỏi, góp ý hoặc yêu cầu hỗ trợ. Chúng tôi phản hồi trong giờ làm việc.</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="contact-card">
                    <h5 class="mb-3">Gửi yêu cầu</h5>
                    <form action="{{ route('user.contact.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="HoTen" value="{{ old('HoTen') }}" class="form-control @error('HoTen') is-invalid @enderror" required>
                            @error('HoTen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="Email" value="{{ old('Email') }}" class="form-control @error('Email') is-invalid @enderror" required>
                            @error('Email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="SDT" value="{{ old('SDT') }}" class="form-control @error('SDT') is-invalid @enderror">
                            @error('SDT')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="TieuDe" value="{{ old('TieuDe') }}" class="form-control @error('TieuDe') is-invalid @enderror" required>
                            @error('TieuDe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea name="NoiDung" rows="4" class="form-control @error('NoiDung') is-invalid @enderror" required>{{ old('NoiDung') }}</textarea>
                            @error('NoiDung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-success">Gửi liên hệ</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-card mb-3">
                    <h5 class="mb-2">Thông tin hỗ trợ</h5>
                    <p class="mb-1"><i class="fa-solid fa-envelope text-primary me-2"></i> support@organicshop.vn</p>
                    <p class="mb-1"><i class="fa-solid fa-phone text-success me-2"></i> 1900 6868 (8:00 - 20:00)</p>
                    <p class="mb-3"><i class="fa-solid fa-location-dot text-danger me-2"></i> 123 Đường Xanh, Quận 5, TP.HCM</p>
                    <div class="d-flex gap-2">
                        <span class="badge text-bg-success">Đổi trả trong 48h</span>
                        <span class="badge text-bg-info">Giao nhanh 2h</span>
                        <span class="badge text-bg-warning text-dark">Hỗ trợ 24/7</span>
                    </div>
                </div>

                <div class="contact-card">
                    <h5 class="mb-3">Câu hỏi thường gặp</h5>
                    @php
                        $faqs = [
                            ['q' => 'Thời gian giao hàng bao lâu?', 'a' => 'Thông thường 2-4 giờ nội thành, 1-2 ngày cho các tỉnh lân cận.'],
                            ['q' => 'Chính sách đổi trả thế nào?', 'a' => 'Đổi/hoàn trong 48h nếu sản phẩm hỏng, kém chất lượng. Giữ lại hóa đơn và hình ảnh.'],
                            ['q' => 'Làm sao dùng mã khuyến mãi?', 'a' => 'Nhập mã ở bước thanh toán. Nếu mã hết hạn/hết lượt sẽ có thông báo.'],
                            ['q' => 'Phí vận chuyển được tính ra sao?', 'a' => 'Miễn phí với đơn từ 300.000đ nội thành. Đơn thấp hơn tính theo khu vực và khối lượng.'],
                            ['q' => 'Có hỗ trợ xuất hóa đơn không?', 'a' => 'Có. Ghi chú “xuất hóa đơn” khi đặt hoặc liên hệ hỗ trợ sau khi đặt thành công.'],
                        ];
                    @endphp
                    <div id="faq-list">
                        @foreach($faqs as $index => $item)
                            <div class="faq-item">
                                <div class="faq-question" data-faq-toggle>
                                    <span>{{ $item['q'] }}</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <div class="faq-answer">{{ $item['a'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-faq-toggle]').forEach(item => {
        item.addEventListener('click', () => {
            const parent = item.closest('.faq-item');
            parent.classList.toggle('active');
        });
    });
</script>
@endpush
