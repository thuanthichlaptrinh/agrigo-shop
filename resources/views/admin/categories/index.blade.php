<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link
            href="/template/Assets/vendor/boxicons/boxicons.min.css"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="/template/Assets/vendor/fontawesome/6.5.2/css/all.min.css"
            integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <link rel="stylesheet" href="/template/admin/style.css" />
        <link rel="stylesheet" href="/template/admin/products.css" />
        <title>Quản lý danh mục - ADMIN</title>
        <style>
            /* Style cho phân trang */
            .custom-pagination {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .custom-button {
                display: inline-block;
                padding: 10px 15px;
                background-color: #f8f9fa;
                color: #007bff;
                text-decoration: none;
                border-top: 1px solid #ddd;
                border-bottom: 1px solid #ddd;
                border-right: 1px solid #ddd;
                font-size: 12px;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            /* Khi hover lên các button */
            .custom-button:hover {
                background-color: #007bff;
                color: white;
            }

            /* Style cho nút hiện tại (active) */
            .custom-button.active {
                background-color: #007bff;
                color: white;
            }

            /* Style cho các button điều hướng (<<, >>) */
            .custom-button:first-child,
            .custom-button:last-child {
                background-color: #f1f1f1;
            }

            .custom-button:first-child {
                border-top-left-radius: 4px;
                border-bottom-left-radius: 4px;
                border-left: 1px solid #ddd;
            }
            .custom-button:last-child {
                border-top-right-radius: 4px;
                border-bottom-right-radius: 4px;
            }

            .custom-button:first-child:hover,
            .custom-button:last-child:hover {
                background-color: #dcdcdc;
            }
        </style>
    </head>
    <body>
        <!-- SIDEBAR -->
        @include('admin.partials.sidebar')
        <!-- SIDEBAR -->

        <!-- NAVBAR -->
        <section id="content">
            <!-- NAVBAR -->
            <nav>
                <i class="bx bx-menu toggle-sidebar"></i>
                <form action="#">
                    <div class="form-group">
                        <input type="text" placeholder="Search..." />
                        <i class="bx bx-search icon"></i>
                    </div>
                </form>
                <a href="#" class="nav-link">
                    <i class="bx bxs-bell icon"></i>
                    <span class="badge">5</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bx bxs-message-square-dots icon"></i>
                    <span class="badge">8</span>
                </a>
                <span class="divider"></span>
                <div class="profile">
                    <img
                        src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
                        alt=""
                    />
                    <ul class="profile-link">
                        <li>
                            <a href="#"
                                ><i class="bx bxs-user-circle icon"></i> Hồ
                                sơ</a
                            >
                        </li>
                        <li>
                            <a href="#"><i class="bx bxs-cog"></i> Cài đặt</a>
                        </li>
                        <li>
                            <a href="#"
                                ><i class="bx bxs-log-out-circle"></i> Đăng
                                xuất</a
                            >
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- NAVBAR -->

            <!-- MAIN -->
            <main>
                <h1 class="title">Quản lý Category</h1>
                <ul class="breadcrumbs">
                    <li><a href="#">Home</a></li>
                    <li class="divider">/</li>
                    <li><a href="#" class="active">Category</a></li>
                </ul>

                <!-- <div style="margin-top: 20px;">
                    <form action="~/Admin/QuanLyLoaiSanPham/DanhSachLoaiSanPham" method="get" style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="search" name="Search" value="@ViewBag.Search" placeholder="Nhập từ khóa..." style="padding: 8px 12px; width: 340px; border: 1px solid #cfcfcf; border-radius: 4px; height: 40px; box-sizing: border-box; font-size: 15px;" />
                
                            <select name="SortName" style="padding: 8px 12px; border: 1px solid #cfcfcf; cursor: pointer; border-radius: 4px; height: 40px; font-size: 15px;">
                                <option value="">Xếp theo tiêu chí</option>
                                <option value="tentang" @(ViewBag.SortName == "tentang" ? "selected" : "")>Tên từ A-Z</option>
                                <option value="tengiam" @(ViewBag.SortName == "tengiam" ? "selected" : "")>Tên từ Z-A</option>
                            </select>
                
                            <button type="submit" style="background-color: #1c75e7; border: none; padding: 8px 12px; border-radius: 4px; font-size: 15px; color: white; height: 40px; cursor: pointer;">
                                <i class="fa-solid fa-arrow-rotate-right" style="font-size: 14px;"></i>
                                <span style="font-weight: 500;">Làm mới</span>
                            </button>
                        </div>
                
                        <div>
                            <a href="~/Admin/QuanLyLoaiSanPham/ThemLoaiSanPham"
                               style="display: inline-flex; align-items: center; padding: 10px 16px; background-color: #1c75e7; color: white; border-radius: 4px; font-size: 15px; height: 40px; text-decoration: none;">
                                <i class="fa-solid fa-plus"></i>
                                <span style="margin-left: 6px;">Thêm mới</span>
                            </a>
                        </div>
                    </form>
                </div> -->

                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h6>Table</h6>
                        </div>
                        <div class="table-wrapper">
                            <table class="authors-table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên danh mục</th>
                                        <th>Số lượng</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="tableProduct">
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div class="author-info">
                                                <div>
                                                    <small>Aó khoác nam</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="sdt">120</p>
                                        </td>
                                        <td>
                                            <a href="#" class="edit-link"
                                                ><i
                                                    class="fa-solid fa-pen-to-square"
                                                ></i
                                            ></a>
                                            <a href="#" class="delete-link"
                                                ><i
                                                    class="fa-solid fa-trash"
                                                ></i
                                            ></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <div class="author-info">
                                                <div>
                                                    <small>Aó khoác nữ</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="sdt">120</p>
                                        </td>
                                        <td>
                                            <a href="#" class="edit-link"
                                                ><i
                                                    class="fa-solid fa-pen-to-square"
                                                ></i
                                            ></a>
                                            <a href="#" class="delete-link"
                                                ><i
                                                    class="fa-solid fa-trash"
                                                ></i
                                            ></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>
                                            <div class="author-info">
                                                <div>
                                                    <small>Khác</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="sdt">120</p>
                                        </td>
                                        <td>
                                            <a href="#" class="edit-link"
                                                ><i
                                                    class="fa-solid fa-pen-to-square"
                                                ></i
                                            ></a>
                                            <a href="#" class="delete-link"
                                                ><i
                                                    class="fa-solid fa-trash"
                                                ></i
                                            ></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="custom-pagination">
                    <a href="#" class="custom-button">&laquo;</a>
                    <a href="#" class="custom-button active">1</a>
                    <a href="#" class="custom-button">2</a>
                    <a href="#" class="custom-button">3</a>
                    <a href="#" class="custom-button">4</a>
                    <a href="#" class="custom-button">&raquo;</a>
                </div>
            </main>
            <!-- MAIN -->
        </section>
        <!-- NAVBAR -->

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="/template/admin/script.js"></script>
    </body>
</html>
