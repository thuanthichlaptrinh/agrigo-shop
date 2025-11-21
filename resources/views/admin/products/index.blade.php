@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm - Admin')

@section('content')
<h1 class="title">Quản lý sản phẩm</h1>
<ul class="breadcrumbs">
    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Sản phẩm</a></li>
</ul>

<div class="data">
    <div class="content-data">
        <div class="head">
            <h3>Danh sách sản phẩm</h3>
            <div class="menu">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Thêm sản phẩm
                </a>
            </div>
        </div>
        
        <!-- Search and Filter -->
        <div class="filter-section">
            <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                <select name="category" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories ?? [] as $category)
                    <option value="{{ $category->ID }}" {{ request('category') == $category->ID ? 'selected' : '' }}>
                        {{ $category->TenDanhMuc }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary">Lọc</button>
            </form>
        </div>

        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products ?? [] as $product)
                    <tr>
                        <td>{{ $product->ID }}</td>
                        <td>
                            <img src="{{ $product->HinhAnh }}" alt="{{ $product->TenSanPham }}" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>{{ $product->TenSanPham }}</td>
                        <td>{{ number_format($product->Gia) }}đ</td>
                        <td>{{ $product->SoLuongTon }}</td>
                        <td>{{ $product->loaiSanPham->TenLoai ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $product->TrangThai ? 'success' : 'danger' }}">
                                {{ $product->TrangThai ? 'Hoạt động' : 'Ẩn' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->ID) }}" class="btn btn-sm btn-warning">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->ID) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Không có sản phẩm nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $products->links() ?? '' }}
        </div>
    </div>
</div>
@endsection
