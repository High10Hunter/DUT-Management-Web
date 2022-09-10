<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title> {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">

    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{ asset('css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style"
        disabled="disabled">
    @stack('css')

</head>

<body class="" data-admin_layout="detached"
    data-admin_layout-config="{&quot;leftSidebarCondensed&quot;:false,&quot;darkMode&quot;:false, &quot;showRightSidebarOnStart&quot;: true}">

    @include('admin_layout.topbar')

    <!-- Start Content-->
    <div class="container-fluid mm-active">

        <!-- Begin page -->
        <div class="wrapper mm-show">

            @include('admin_layout.sidebar')

            <div class="content-page" style="padding-top: 20px">
                <div class="content">

                    <div class="container-fluid">
                        <!-- start page title -->
                        @yield('content')
                    </div>
                    <!-- end page title -->

                </div> <!-- End Content -->

                @include('admin_layout.footer')

            </div> <!-- content-page -->

        </div> <!-- end wrapper-->
    </div>
    <!-- END Container -->


    <!-- bundle -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    @stack('js')

</body>

</html>
