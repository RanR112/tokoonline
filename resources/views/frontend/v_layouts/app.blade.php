<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon_univ_bsi.png') }}">
    <title>tokoonline</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Hind:400,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/slick-theme.css') }}">

    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/nouislider.min.css') }}">

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>

<body>
    <!-- HEADER -->
    <header>
        <!-- top Header -->
        <div id="top-header">
            <div class="container">
                <div class="pull-left">
                    <span>Selamat datang di toko online</span>
                </div>
            </div>
        </div>
        <!-- /top Header -->

        <!-- header -->
        <div id="header">
            <div class="container">
                <div class="pull-left">
                    <!-- Logo -->
                    <div class="header-logo">
                        <a class="logo" href="#">
                            <img src="{{ asset('image/icon_univ_bsi.png') }}" alt="">
                        </a>
                    </div>
                    <!-- /Logo -->

                    <!-- Search -->

                    <!-- /Search -->
                </div>
                <div class="pull-right">
                    <ul class="header-btns">
                        <!-- Cart -->
                        <li class="header-cart dropdown default-dropdown">
                            <a href="{{ route('order.cart') }}" class="text-uppercase">
                                <div class="header-btns-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <strong class="text-uppercase">Keranjang</strong>
                            </a>
                        </li>
                        <!-- /Cart -->

                        @if (Auth::check())
                            <!-- Account -->
                            <li class="header-account dropdown default-dropdown">
                                <div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
                                    <div class="header-btns-icon">
                                        <i class="fa fa-user-o"></i>
                                    </div>
                                    <strong class="text-uppercase">{{ Auth::user()->nama }}<i
                                            class="fa fa-caret-down"></i></strong>
                                </div>
                                <ul class="custom-menu">
                                    <li><a href="{{ route('customer.akun', ['id' => Auth::user()->id]) }}"><i
                                                class="fa fa-user-o"></i> Akun Saya</a></li>
                                    <li><a href="{{ route('order.history') }}"><i class="fa fa-check"></i> History</a></li>
                                    <li>
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('keluar-app').submit();"><i
                                                class="fa fa-power-off"></i> Keluar
                                        </a>
                                        <!-- form keluar app -->
                                        <form id="keluar-app" action="{{ route('customer.logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                        <!-- form keluar app end -->
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="header-account dropdown default-dropdown">
                                <div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
                                    <div class="header-btns-icon">
                                        <i class="fa fa-user-o"></i>
                                    </div>
                                    <strong class="text-uppercase">Akun Saya<i class="fa fa-caret-down"></i></strong>
                                </div>
                                <a href="{{ route('auth.redirect') }}" class="text-uppercase">Login</a>
                            </li>
                            <!-- /Account -->
                        @endif

                        <!-- Mobile nav toggle-->
                        <li class="nav-toggle">
                            <button class="nav-toggle-btn main-btn icon-btn"><i class="fa fa-bars"></i></button>
                        </li>
                        <!-- / Mobile nav toggle -->
                    </ul>
                </div>
            </div>
            <!-- header -->
        </div>
        <!-- container -->
    </header>
    <!-- /HEADER -->

    <!-- NAVIGATION -->
    <div id="navigation">
        <!-- container -->
        <div class="container">
            <div id="responsive-nav">
                @php
                    $kategori = DB::table('kategori')->orderBy('nama_kategori', 'asc')->get();
                @endphp
                @if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
                    <!-- category nav -->
                    <div class="category-nav">
                        <span class="category-header">Kategori <i class="fa fa-list"></i></span>
                        <ul class="category-list">
                            @foreach ($kategori as $row)
                                <li><a
                                        href="{{ route('frontend.produk.produkkategori', $row->id) }}">{{ $row->nama_kategori }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <ul class="category-list">
                    </div>
                @else
                    <div class="category-nav show-on-click">
                        <span class="category-header">Kategori <i class="fa fa-list"></i></span>
                        <ul class="category-list">
                            @foreach ($kategori as $row)
                                <li><a
                                        href="{{ route('frontend.produk.produkkategori', $row->id) }}">{{ $row->nama_kategori }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /category nav -->
                @endif

                <!-- menu nav -->
                <div class="menu-nav">
                    <span class="menu-header">Menu <i class="fa fa-bars"></i></span>
                    <ul class="menu-list">
                        <li><a href="{{ route('frontend.beranda') }}">Beranda</a></li>
                        <li><a href="{{ route('frontend.produk.all') }}">Produk</a></li>
                        <li><a href="#">Lokasi</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
                <!-- menu nav -->
            </div>
        </div>
        <!-- /container -->
    </div>
    <!-- /NAVIGATION -->

    @if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
        <!-- HOME -->
        <div id="home">
            <!-- container -->
            <div class="container">
                <!-- home wrap -->
                <div class="home-wrap">
                    <!-- home slick -->
                    <div id="home-slick">
                        <!-- banner -->
                        <div class="banner banner-1">
                            <img src="{{ asset('frontend/banner/banner01.jpg') }}" alt="">
                            <div class="banner-caption text-center">
                                <h1>Jajanan Tradisional</h1>
                                <h3 class="black-color font-weak">Khas makanan indonesia</h3>
                                <button class="primary-btn">Pesan Sekarang</button>
                            </div>
                        </div>
                        <!-- /banner -->

                        <!-- banner -->
                        <div class="banner banner-1">
                            <img src="{{ asset('frontend/banner/banner02.jpg') }}" alt="">
                            <div class="banner-caption">
                                <h1 class="primary-color">Khas makanan indonesia<br><span
                                        class="white-color font-weak">Up to 50% OFF</span></h1>
                                <button class="primary-btn">Pesan sekarang</button>
                            </div>
                        </div>
                        <!-- /banner -->

                        <!-- banner -->
                        <div class="banner banner-1">
                            <img src="{{ asset('frontend/banner/banner03.jpg') }}" alt="">
                            <div class="banner-caption">
                                <h1 class="black-color">Produk Baru <span>Indonesia</span></h1>
                                <button class="primary-btn">Pesan Sekarang</button>
                            </div>
                        </div>
                        <!-- /banner -->
                    </div>
                    <!-- /home slick -->
                </div>
                <!-- /home wrap -->
            </div>
            <!-- /container -->
        </div>
        <!-- /HOME -->
    @endif

    <!-- section -->
    <div class="section mt-4" style="margin-top: 3rem">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- ASIDE -->
                <div id="aside" class="col-md-3">
                    <!-- aside widget -->
                    <div class="aside">
                        <h3 class="aside-title">Top Rated Product</h3>
                        <!-- widget product -->
                        <div class="product product-widget">
                            <div class="product-thumb">
                                <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="">
                            </div>
                            <div class="product-body">
                                <h2 class="product-name"><a href="#">Product Name Goes Here</a></h2>
                                <h3 class="product-price">$32.50 <del class="product-old-price">$45.00</del></h3>
                                <div class="product-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-o empty"></i>
                                </div>
                            </div>
                        </div>
                        <!-- /widget product -->

                        <!-- widget product -->
                        <div class="product product-widget">
                            <div class="product-thumb">
                                <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="">
                            </div>
                            <div class="product-body">
                                <h2 class="product-name"><a href="#">Product Name Goes Here</a></h2>
                                <h3 class="product-price">$32.50</h3>
                                <div class="product-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-o empty"></i>
                                </div>
                            </div>
                        </div>
                        <!-- /widget product -->
                    </div>
                    <!-- /aside widget -->
                    <!-- aside widget -->
                    <div class="aside">
                        <h3 class="aside-title">Filter Kategori</h3>
                        <ul class="list-links">
                            @foreach ($kategori as $row)
                                <li><a
                                        href="{{ route('frontend.produk.produkkategori', $row->id) }}">{{ $row->nama_kategori }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /aside widget -->
                </div>
                <!-- /ASIDE -->

                <!-- MAIN -->
                <div id="main" class="col-md-9">
                    <!-- store top filter -->
                    <!-- /store top filter -->

                    <!-- @yieldAwal -->
                    @yield('content')
                    <!-- @yieldAkhir -->

                    <!-- store bottom filter -->

                    <!-- /store bottom filter -->
                </div>
                <!-- /MAIN -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /section -->

    <!-- FOOTER -->
    <footer id="footer" class="section section-grey">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- footer widget -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <!-- footer logo -->
                        <div class="footer-logo">
                            <a class="logo" href="#">
                                <img src="./img/logo.png" alt="">
                            </a>
                        </div>
                        <!-- /footer logo -->

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna</p>

                        <!-- footer social -->
                        <ul class="footer-social">
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                        </ul>
                        <!-- /footer social -->
                    </div>
                </div>
                <!-- /footer widget -->

                <!-- footer widget -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">My Account</h3>
                        <ul class="list-links">
                            <li><a href="#">My Account</a></li>
                            <li><a href="#">My Wishlist</a></li>
                            <li><a href="#">Compare</a></li>
                            <li><a href="#">Checkout</a></li>
                            <li><a href="#">Login</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /footer widget -->

                <div class="clearfix visible-sm visible-xs"></div>

                <!-- footer widget -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Customer Service</h3>
                        <ul class="list-links">
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Shiping & Return</a></li>
                            <li><a href="#">Shiping Guide</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /footer widget -->

                <!-- footer subscribe -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Stay Connected</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.</p>
                        <form>
                            <div class="form-group">
                                <input class="input" placeholder="Enter Email Address">
                            </div>
                            <button class="primary-btn">Join Newslatter</button>
                        </form>
                    </div>
                </div>
                <!-- /footer subscribe -->
            </div>
            <!-- /row -->
            <hr>
            <!-- row -->
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <!-- footer copyright -->
                    <div class="footer-copyright">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved | This template is made with <i
                            class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com"
                            target="_blank">Colorlib</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                    <!-- /footer copyright -->
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </footer>
    <!-- /FOOTER -->

    <!-- jQuery Plugins -->
    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>

</body>

</html>
