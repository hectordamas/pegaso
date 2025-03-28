<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @yield("metadata")
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/adminty/assets/images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/slicknav.min.css') }}" >
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/strdash/css/responsive.css') }}">
    <!-- modernizr css -->
    <script src="{{ asset('assets/strdash/js/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>

<body>

    <div id="preloader">
        <div class="loader"></div>
    </div>

    @yield('content')

    <!-- jquery latest version -->
    <script src="{{ asset('assets/strdash/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <!-- bootstrap 4 js -->
    <script src="{{ asset('assets/strdash/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/strdash/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/strdash/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/strdash/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/strdash/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/strdash/js/jquery.slicknav.min.js') }}"></script>
    
    <!-- others plugins -->
    <script src="{{ asset('assets/strdash/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/strdash/js/scripts.js') }}"></script>
</body>

</html>
