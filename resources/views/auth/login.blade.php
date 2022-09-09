<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Đăng nhập | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"> --}}
    {{-- <meta content="Coderthemes" name="author"> --}}
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-modern.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style"
        disabled="disabled">

</head>

<body class="authentication-bg pb-0" data-layout-config="{&quot;darkMode&quot;:false}">

    <div class="auth-fluid">
        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            <div class="align-items-center d-flex h-100">
                <div class="card-body">

                    <!-- Logo -->
                    <div class="auth-brand text-center text-lg-left">
                        <a href="">
                            <span><img src="{{ asset('university_images/logo.jpg') }}" alt=""
                                    height="100"></span>
                        </a>
                    </div>

                    <!-- title-->
                    <h4 class="mt-0">Đăng nhập</h4>
                    <p class="text-muted mb-4">
                        Nhập tài khoản và mật khẩu để đăng nhập
                    </p>

                    {{-- show errors --}}
                    @if (session()->has('error'))
                        <div class="alert alert-danger text-center">
                            {{ session()->get('error') }}
                            {{ session()->forget('error') }}
                        </div>
                    @endif

                    <!-- form -->
                    <form action="{{ route('logining') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role">
                        <div class="form-group">
                            <label for="username">Tài khoản</label>
                            <input class="form-control" type="text" id="username-input" name="username"
                                placeholder="Nhập tài khoản">
                            <span id="username-error" class="display_error" style="color: red"></span>
                        </div>
                        <div class="form-group">
                            <a href="pages-recoverpw-2.html" class="text-muted float-right">
                                <small>Quên mật khẩu</small>
                            </a>
                            <label for="password">Mật khẩu</label>
                            <input class="form-control" type="password" id="password-input" name="password"
                                placeholder="Nhập mật khẩu">
                            <span id="password-error" class="display_error" style="color: red"></span>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkbox-signin"
                                    name="remember_me" value="1">
                                <label class="custom-control-label" for="checkbox-signin">Ghi nhớ đăng nhập</label>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-center">
                            <button class="btn btn-primary btn-block" type="submit" onclick="return validate_form()">
                                <i class="mdi mdi-login"></i> Đăng nhập
                            </button>
                        </div>
                    </form>
                    <!-- end form-->

                </div> <!-- end .card-body -->

            </div> <!-- end .align-items-center.d-flex.h-100-->
        </div>
        <!-- end auth-fluid-form-box-->

        <!-- Auth fluid right content -->
        <div class="auth-fluid-right text-center "
            style=" background-image: url('{{ asset('university_images/university_buliding.jpg') }}');
            background-position-x: center;
            background-position-y: center;
            background-size: cover;">

        </div>
        <!-- end Auth fluid right content -->
    </div>
    <!-- end auth-fluid-->

    <!-- bundle -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script type="text/javascript">
        //form validation
        function validate_form() {
            let check = true;
            let username = document.getElementById('username-input').value;
            if (username.length == 0) {
                document.getElementById('username-error').innerHTML = "Vui lòng nhập tài khoản";
                check = false;
            } else {
                document.getElementById('username-error').innerHTML = "";
            }

            let password = document.getElementById('password-input').value;
            if (password.length == 0) {
                document.getElementById('password-error').innerHTML = "Mật khẩu không được để trống";
                check = false;
            } else {
                document.getElementById('password-error').innerHTML = "";
            }
            if (check == false)
                return false;
            return true;
        }
    </script>

</body>

</html>
