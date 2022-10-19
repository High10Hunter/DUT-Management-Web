<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Trang chủ | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{ asset('css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style"
        disabled="disabled">

</head>

<body class="" data-layout="detached"
    data-layout-config="{&quot;leftSidebarCondensed&quot;:false,&quot;darkMode&quot;:false, &quot;showRightSidebarOnStart&quot;: true}">

    @include('admin_layout.topbar')

    <!-- Start Content-->
    <div class="container-fluid mm-active">

        <!-- Begin page -->
        <div class="wrapper mm-show">


            <div class="content-page">
                <div class="content">

                    <div class="row mb-5"></div>

                    <div class="row d-flex justify-content-center">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body bg-primary">
                                    <h3 class="card-title" style="text-align:center">
                                        <a href="{{ route('admin.users.index') }}" class="stretched-link text-white">
                                            <i class="dripicons-user"></i>
                                            Quản lý người dùng
                                        </a>
                                    </h3>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body" style="background-color:rgb(237, 237, 9)">
                                    <h3 class="card-title" style="text-align:center">
                                        <a href="{{ route('admin.courses.index') }}"
                                            class="stretched-link text-black-50">
                                            <i class="uil-invoice"></i>
                                            Quản lý khoá
                                        </a>
                                    </h3>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col-->

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body" style="background-color:rgb(82, 181, 84)">
                                    <h3 class="card-title" style="text-align:center">
                                        <a href="{{ route('admin.subjects.index') }}" class="stretched-link text-white">
                                            <i class="mdi mdi-folder-text"></i>
                                            Quản lý môn - Chuyên ngành
                                        </a>
                                    </h3>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col-->

                        <div class="col-lg-2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body" style="background-color:purple">
                                    <h3 class="card-title" style="text-align:center">
                                        <a href="{{ route('admin.modules.index') }}" class="stretched-link text-white">
                                            <i class="mdi mdi-calendar-account"></i>
                                            Giảng dạy
                                        </a>
                                    </h3>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col-->

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body" style="background-color:rgb(227, 34, 227)">
                                    <h3 class="card-title" style="text-align:center">
                                        <a href="{{ route('admin.exams.index') }}" class="stretched-link text-white">
                                            <i class="mdi mdi-calendar-month"></i>
                                            Xếp lịch thi
                                        </a>
                                    </h3>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col-->

                        <div class="col-lg-2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body" style="background-color:rgba(30, 8, 232, 0.953)">
                                    <h3 class="card-title" style="text-align:center">
                                        <a href="{{ route('admin.students.index') }}"
                                            class="stretched-link text-white">
                                            <i class="mdi mdi-school"></i>
                                            Quản lý sinh viên
                                        </a>
                                    </h3>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col-->

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body" style="background-color:rgba(81, 4, 49, 0.719)">
                                    <h3 class="card-title" style="text-align:center">
                                        <a href="#" class="stretched-link text-white">
                                            <i class="mdi mdi-file-document-edit"></i>
                                            Quản lý điểm
                                        </a>
                                    </h3>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col-->


                        <div class="col-lg-2">
                        </div>
                    </div>
                </div> <!-- End Content -->
            </div> <!-- End Content -->

            @include('admin_layout.footer')

        </div> <!-- content-page -->

    </div> <!-- end wrapper-->
    </div>
    <!-- END Container -->


    <!-- bundle -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>

</body>

</html>
