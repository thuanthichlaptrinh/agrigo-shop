<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <link rel="stylesheet" href="./Assets/Bootstrap5/css/bootstrap.min.css" />
        <link rel="stylesheet" href="./Assets/css/base.css" />
        <link rel="stylesheet" href="./Assets/css/animation.css" />
        <link rel="stylesheet" href="./Assets/css/style.css" />
        <script src="./Assets/Bootstrap5/js/bootstrap.min.js"></script>
        <script src="./Assets/js/main.js"></script>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.4.0/remixicon.css"
            integrity="sha512-hH7VMMVfPgfkpYx2GazOEG6RrYM+y8cS7FzccwBTWQeyhPv01XYk0MVcuhh4EAimOELWvqKjhNwes/UsYoyN6w=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
    </head>
    <body>
        <header class="header">
            <div class="container-fluid" style="background: var(--bg-primary)">
                <div class="container">
                    <div class="row">
                        <div class="navbar navbar-expand-sm" style="margin-left: 4px; margin-top: 4px">
                            <div class="col-3">
                                <a href="#" class="navbar-brand text-white">
                                    <b style="color: #ffea09; font-size: 25px">Organic Shop</b>
                                    <!-- <img src="./Assets/Images/logo2.png" style="width: 150px; height: 50px; object-fit: cover" alt="" /> -->
                                </a>
                            </div>

                            <form class="col-6 d-flex align-items-center">
                                <button type="submit" style="margin-right: -40px; border: none; background-color: transparent; z-index: 2">
                                    <i class="ri-search-line fs-22-t" style="color: green"></i>
                                </button>
                                <input type="search" class="form-control w-100 header-search" style="padding-left: 42px" placeholder="Bạn tìm gì ở nông sản xanh - nhóm 2" />
                                <a href="#" class="nav-link text-white align-items-centers cart">
                                    <i class="ri-shopping-cart-line d-flex align-content-center justify-content-center"></i>
                                    <span>3</span>
                                </a>
                            </form>

                            <div class="col-3">
                                <ul class="navbar-nav" style="float: right">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link text-white location" style="padding-right: 3px">
                                            <i class="ri-map-pin-line fs-17-t"></i>
                                            <span>Địa chỉ:</span>
                                            <span class="fw-700" style="margin-left: 4px; color: rgb(255 255 255 / 1)">Thành phố Hồ Chí Minh, P5, Q8</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3" style="margin-left: -12px">
                            <div class="d-flex align-items-center py-0 text-white bg-menu" style="border-top-left-radius: 8px; border-top-right-radius: 8px; width: 300.5px">
                                <i class="ri-menu-line mx-2" style="font-size: 22px"></i>
                                <span class="text-uperc fw-500 fs-16-t">Danh mục sản phẩm</span>
                            </div>
                        </div>
                        <div class="col-7">
                            <div></div>
                        </div>
                        <div class="col-2" style="padding-left: 0; position: relative; right: -24px">
                            <div class="float-end">
                                <div class="d-flex bg-menu text-white align-items-center text-center px-2" style="height: 33px; border-top-left-radius: 8px; border-top-right-radius: 8px">
                                    <i class="ri-user-line" style="display: flex; font-size: 18px; align-items: center; justify-content: center; margin-bottom: -2px"></i>
                                    <a href="./login.html" class="d-block text-white" style="text-decoration: none; padding-left: 5px">Tài khoản của Thuận</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="container" style="margin-top: var(--header-height)">
            <div class="row">
                <div class="col-3 slide-menu p-0">
                    <ul class="nav flex-column bg-white">
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Rau, củ, quả</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Thịt, cá, trứng, hải sản</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Gạo, bột, đồ khô</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Dầu ăn, nước chấm, gia vị</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Kem, thực phẩm đông mát</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Rau, củ, quả</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Thịt, cá, trứng, hải sản</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Gạo, bột, đồ khô</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Kem, thực phẩm đông mát</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Rau, củ, quả</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Thịt, cá, trứng, hải sản</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Gạo, bột, đồ khô</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Gạo, bột, đồ khô</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link border-b mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Kem, thực phẩm đông mát</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link mx-3 p-0 py-2 text-dark fw-500 text-uperc fs-14-t">Rau, củ, quả</a>
                        </li>
                    </ul>
                </div>

                <div class="col-9 content-body">
                    <!-- Danh muc -->
                    <div class="row" style="margin-left: -23px">
                        <div class="pro-cate d-flex align-items-center bg-white">
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/icon-history.v202301091407.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p style="font-size: 14px; color: rgb(0 126 66 / 1); font-weight: bold; margin-top: 5px; line-height: 1.2">Mua lại đơn cũ</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/raucuqua.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Rau, củ, trái cây</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/sua-tuoi-202210311320147075_202408291415515944.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Sữa tươi</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/gao-cac-loai-202209301453236694.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Gạo các loại</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/rau-la-cac-loai-202210311314254141.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Rau lá</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/trai-cay-cac-loai-202210311314516525.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Trái cây</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/rau-cu-cac-loai-202209301506432108.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Củ quả</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/274034_202410110846451339.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Kẹo các loại</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/image-1_202410181218245372.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Thịt heo</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/image-2466_202410151406552676.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Rau, củ làm sẵn</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/ca-hai-san-202209301439213205.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Cá, hải sản khô</p>
                                </div>
                            </div>
                            <div class="" style="width: calc(100% / 12)">
                                <img src="./Assets/Images/trung-ga-vit-cut-202212051414238645.png" class="w-75" alt="" />
                                <div class="pro-cate-body" style="padding-left: 5px; padding-right: 5px">
                                    <p>Trứng gà, vịt, cút</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banner -->
                    <div class="row mt-2" style="margin-left: -34px; margin-right: -24px">
                        <div id="demo" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                                <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                                <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
                            </div>

                            <div class="carousel-inner">
                                <div class="carousel-item">
                                    <img src="./Assets/Images/hoa-don-maggi-50k.jpg" alt="New York" class="d-block w-100" />
                                </div>
                                <div class="carousel-item active">
                                    <img src="./Assets/Images/qcbanner6-61-hinh.png" style="width: 1000.01px; height: 255.01px; object-fit: cover" alt="Chicago" class="d-block w-100" />
                                </div>
                                <div class="carousel-item">
                                    <img src="./Assets/Images/s-4395-hinh.png" style="width: 1000.01px; height: 255.01px; object-fit: cover" alt="Los Angeles" class="d-block w-100" />
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Khuiyen mai -->
                    <div class="row mt-3" style="background-color: #effff2; margin-left: -23px; padding-left: 10px">
                        <div class="d-flex justify-content-between mt-2 pl-22-t">
                            <h5 style="color: var(--text-primary); margin-top: 8px" class="fw-700">KHUYẾN MÃI SỐC</h5>
                            <a href="" class="fs-14-t d-flex align-items-center" style="text-decoration: none">
                                Xem thêm khuyến mãi
                                <i class="ri-arrow-right-s-line fs-20-t mt-3-t fw-700"></i>
                            </a>
                        </div>

                        <div class="row pl-22-t" style="padding-right: 0; margin-bottom: 13px; margin-top: 6px">
                            <div class="col-km col-lg-3 col-md-4 col-sm-12 mb-2" style="position: relative">
                                <div class="d-flex align-items-center justify-content-center box-flash">
                                    <i class="ri-flashlight-line"></i>
                                    <span style="font-weight: 700">-34%</span>
                                </div>

                                <div class="p-1" style="border-radius: 4px; background: linear-gradient(180deg, #8eca51, #2c8e5f 84.5%, #5e9329)">
                                    <a href="">
                                        <img src="./Assets/Images/thumb-2024-26_202410171658026192.jpg" class="w-100" alt="" />
                                    </a>
                                    <div>
                                        <p class="bg-white w-100 mt-1 text-center text-secondary m-0" style="border-radius: 20px; padding: 0px; font-size: 12px; font-weight: 500">Còn 25 suất</p>
                                        <div class="d-flex justify-content-between px-2 py-2">
                                            <div>
                                                <p class="txt-yellow m-0" style="font-weight: 700">25.500đ</p>
                                                <p class="text-white m-0 fw-500 fs-13-t" style="text-decoration: line-through">28.000đ</p>
                                            </div>
                                            <a href="#" class="btn btn-buy bg-white d-block float-end txt-primary mt-2" style="width: 100px; height: 40px">
                                                <b>MUA</b>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-km col-lg-3 col-md-4 col-sm-12 mb-2">
                                <div class="p-1" style="border-radius: 4px; background: linear-gradient(180deg, #8eca51, #2c8e5f 84.5%, #5e9329)">
                                    <a href="">
                                        <img src="./Assets/Images/thumb-2024-26_202410171658026192.jpg" class="w-100" alt="" />
                                    </a>
                                    <div>
                                        <p class="bg-white w-100 mt-1 text-center text-secondary m-0" style="border-radius: 20px; padding: 0px; font-size: 12px; font-weight: 500">Còn 25 suất</p>
                                        <div class="d-flex justify-content-between px-2 py-2">
                                            <div>
                                                <p class="txt-yellow m-0" style="font-weight: 700">25.500đ</p>
                                                <p class="text-white m-0 fw-500 fs-13-t" style="text-decoration: line-through">28.000đ</p>
                                            </div>
                                            <a href="#" class="btn btn-buy bg-white d-block float-end txt-primary mt-2" style="width: 100px; height: 40px">
                                                <b>MUA</b>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-km col-lg-3 col-md-4 col-sm-12 mb-2">
                                <div class="p-1" style="border-radius: 4px; background: linear-gradient(180deg, #8eca51, #2c8e5f 84.5%, #5e9329)">
                                    <a href="">
                                        <img src="./Assets/Images/thumb-2024-26_202410171658026192.jpg" class="w-100" alt="" />
                                    </a>
                                    <div>
                                        <p class="bg-white w-100 mt-1 text-center text-secondary m-0" style="border-radius: 20px; padding: 0px; font-size: 12px; font-weight: 500">Còn 25 suất</p>
                                        <div class="d-flex justify-content-between px-2 py-2">
                                            <div>
                                                <p class="txt-yellow m-0" style="font-weight: 700">25.500đ</p>
                                                <p class="text-white m-0 fw-500 fs-13-t" style="text-decoration: line-through">28.000đ</p>
                                            </div>
                                            <a href="#" class="btn btn-buy bg-white d-block float-end txt-primary mt-2" style="width: 100px; height: 40px">
                                                <b>MUA</b>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-km col-lg-3 col-md-4 col-sm-12 mb-2">
                                <div class="p-1" style="border-radius: 4px; background: linear-gradient(180deg, #8eca51, #2c8e5f 84.5%, #5e9329)">
                                    <a href="">
                                        <img src="./Assets/Images/thumb-2024-26_202410171658026192.jpg" class="w-100" alt="" />
                                    </a>
                                    <div>
                                        <p class="bg-white w-100 mt-1 text-center text-secondary m-0" style="border-radius: 20px; padding: 0px; font-size: 12px; font-weight: 500">Còn 25 suất</p>
                                        <div class="d-flex justify-content-between px-2 py-2">
                                            <div>
                                                <p class="txt-yellow m-0" style="font-weight: 700">25.500đ</p>
                                                <p class="text-white m-0 fw-500 fs-13-t" style="text-decoration: line-through">28.000đ</p>
                                            </div>
                                            <a href="#" class="btn btn-buy bg-white d-block float-end txt-primary mt-2" style="width: 100px; height: 40px">
                                                <b>MUA</b>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thit, ca, trung, hai san -->
                    <div class="row mt-3" style="margin-left: -34px; margin-right: -24px; margin-bottom: 25px">
                        <div id="thi-ca-trung" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#thi-ca-trung" data-bs-slide-to="0" class="active"></button>
                                <button type="button" data-bs-target="#thi-ca-trung" data-bs-slide-to="1"></button>
                                <button type="button" data-bs-target="#thi-ca-trung" data-bs-slide-to="2"></button>
                            </div>

                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="./Assets/Images/cate-pc-54_202410191139488622.jpg" alt="Los Angeles" class="d-block w-100" />
                                </div>
                                <div class="carousel-item">
                                    <img src="./Assets/Images/cate-pc-48_202410142214151821.jpg" alt="Chicago" class="d-block w-100" />
                                </div>
                                <div class="carousel-item">
                                    <img src="./Assets/Images/cate-pc-54_202410191139488622.jpg" alt="New York" class="d-block w-100" />
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#thi-ca-trung" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#thi-ca-trung" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3 bg-white mb-13-t pb-12-t row-km" style="margin-left: -23px">
                        <div class="title-banner-wrapper">
                            <div class="triangle-left"></div>
                            <a class="title-banner">
                                <span>RAU, CỦ, NẤM</span>
                            </a>
                            <div class="triangle-right"></div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="card" style="border: 1px solid #d8e1f9">
                                <a href="">
                                    <img src="./Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp" class="w-100" alt="" />
                                </a>
                                <div class="card-body">
                                    <p class="card-title fw-400 txt-gray">Ức gà có xương</p>
                                    <p class="card-title">
                                        <span class="fw-700">28.350đ</span>
                                        <span class="txt-gray fs-13-t">/300g</span>
                                    </p>
                                    <div class="container-ThemVGio">
                                        <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block">Thêm vào giỏ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="card" style="border: 1px solid #d8e1f9">
                                <a href="">
                                    <img src="./Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp" class="w-100" alt="" />
                                </a>
                                <div class="card-body">
                                    <p class="card-title fw-400 txt-gray">Ức gà có xương</p>
                                    <p class="card-title">
                                        <span class="fw-700">28.350đ</span>
                                        <span class="txt-gray fs-13-t">/300g</span>
                                    </p>
                                    <div class="container-ThemVGio">
                                        <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block">Thêm vào giỏ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="card" style="border: 1px solid #d8e1f9">
                                <a href="">
                                    <img src="./Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp" class="w-100" alt="" />
                                </a>
                                <div class="card-body">
                                    <p class="card-title fw-400 txt-gray">Ức gà có xương</p>
                                    <p class="card-title">
                                        <span class="fw-700">28.350đ</span>
                                        <span class="txt-gray fs-13-t">/300g</span>
                                    </p>
                                    <div class="container-ThemVGio">
                                        <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block">Thêm vào giỏ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="card" style="border: 1px solid #d8e1f9">
                                <a href="">
                                    <img src="./Assets/Images/tao_gala_phap_size_100_8aef2b9571944ed0b7a6ee52ea416e3d_large.webp" class="w-100" alt="" />
                                </a>
                                <div class="card-body">
                                    <p class="card-title fw-400 txt-gray">Ức gà có xương</p>
                                    <p class="card-title">
                                        <span class="fw-700">28.350đ</span>
                                        <span class="txt-gray fs-13-t">/300g</span>
                                    </p>
                                    <div class="container-ThemVGio">
                                        <a href="#" class="btn btn-ThemVaoGio text-white mx-auto fw-500 d-block">Thêm vào giỏ</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="" class="text-center mt-2">Xem thêm</a>
                    </div>

                    <!-- Footer -->
                    <footer class="row mb-3" style="margin-right: -24px; margin-left: -11px">
                        <ul class="p-2 d-flex align-items-center mb-0" style="background-color: var(--bg-footer); margin-left: -12px">
                            <li class="text-white fs-14-t fw-500">bán 7:00 - 21:30</li>
                            <li class="mx-3">
                                <a href="" class="fw-700 text-white fs-14-t" style="text-decoration: none"><i class="ri-phone-line"></i> 19001908</a>
                            </li>
                            <li class="text-white fs-14-t fw-500">Khiếu nại 7:00 - 21:30</li>
                            <li class="mx-3">
                                <a href="" class="fw-700 text-white fs-14-t" style="text-decoration: none"><i class="ri-phone-line"></i> 18001067</a>
                            </li>
                            <li class="fw-700 fs-14-t fw-500" style="text-decoration: none; color: yellow; margin-left: 80px; margin-right: 20px">Cam kết:</li>
                            <li class="text-white fs-14-t fw-500"><i class="ri-checkbox-circle-line"></i> 15.000 sản phẩm</li>
                            <li class="text-white mx-3 fs-14-t fw-500"><i class="ri-checkbox-circle-line"></i> Freeship đơn 100k</li>
                            <li class="text-white fw-500 fs-14-t"><i class="ri-checkbox-circle-line"></i> Giao 2h</li>
                        </ul>

                        <div class="row bg-white pt-3">
                            <div class="col-6 d-flex justify-content-between">
                                <ul class="pl-0-t">
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Hướng dẫn mua</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Tích điểm quà tặng VIP</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Cần thuê mặt bằng</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Hỏi đáp</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Tuyển 6542 việc</a>
                                    </li>
                                </ul>

                                <ul class="pl-0-t">
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Hóa đơn điện tử</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Giới thiệu công ty</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Liên hệ</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Quy chế web</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">cảnh báo giả mạo</a>
                                    </li>
                                </ul>

                                <ul class="pl-0-t">
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Chính sách khách hàng</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Chính sách giao hàng</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Chính sách đổi trả</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Trả góp, điện nước</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="text-dark nav-link pb-2 fs-15-t" style="text-decoration: none">Đăng ký chào hàng</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-1"></div>

                            <div class="col-5">
                                <div class="mb-2">
                                    <img src="./Assets/Images/checked.png" style="width: 20px" alt="" />
                                    <span>Cam kết chất lượng, không hài lòng trả hàng !</span>
                                </div>
                                <div class="mb-2">
                                    <img src="./Assets/Images/shipped.png" style="width: 20px" alt="" />
                                    <span>Giao hàng đúng hẹn, trễ tặng ngay 100.000đ</span>
                                </div>
                                <div class="mb-2">
                                    <a href=""><img src="./Assets/Icon/facebook.png" style="width: 20px" alt="" /></a>
                                    <a href=""><img src="./Assets/Icon/youtube.png" class="mx-1" style="width: 20px" alt="" /></a>
                                    <a href=""> <img src="./Assets/Icon/instagram.png" style="width: 20px" alt="" /></a>
                                </div>
                                <div>
                                    <img src="./Assets/Images/ggp-as.png" class="mx-auto d-block" style="width: 280px" alt="" />
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </main>

        <div class="fixed-icon">
            <a href="#" class="scroll-up">
                <img src="./Assets/Images/up-arrow1.png" alt="Scroll Up" />
            </a>
            <a href="">
                <img src="./Assets/Icon/facebook.png" alt="Facebook" />
            </a>
            <a href="">
                <img src="./Assets/Icon/youtube.png" alt="YouTube" />
            </a>
            <a href="">
                <img src="./Assets/Images/phone-call.png" alt="YouTube" />
            </a>
        </div>
    </body>
</html>
