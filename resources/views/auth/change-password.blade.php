<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đổi mật khẩu</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-modern.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style"
        disabled="disabled">
</head>

<body class="" data-admin_layout="detached"
    data-admin_layout-config="{&quot;leftSidebarCondensed&quot;:false,&quot;darkMode&quot;:false, &quot;showRightSidebarOnStart&quot;: true}">

    <!-- Topbar Start -->
    <div class="navbar-custom topnav-navbar topnav-navbar-dark">
        <div class="container-fluid">
            <!-- LOGO -->
            <a href="" class="topnav-logo">
            </a>
            <img src="{{ asset('university_images/logo.jpg') }}" height="50">

            <ul class="list-unstyled topbar-right-menu float-right mb-0">

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown"
                        id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span>
                            <span class="account-user-name">
                                @if (isAdmin() || isEAOStaff())
                                    {{ auth()->user()->eao_staff->name }}
                                @elseif (isLecturer())
                                    {{ auth()->user()->lecturer->name }}
                                @elseif (isStudent())
                                    {{ auth()->user()->student->name }}
                                @endif
                            </span>
                            <span class="account-position">
                                {{ auth()->user()->role_name }}
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                        aria-labelledby="topbar-userdrop">
                        <!-- item-->
                        <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-logout mr-1"></i>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </li>
        </div>
    </div>
    <!-- end Topbar -->
    <div class="container-fluid mm-active">
        <div class="wrapper mm-show">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content col-lg-5 mx-auto">
                                    <h4 class="form-title">Đổi mật khẩu</h4>
                                    <form id="change-password-form" action="{{ route('admin.store_new_password') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        <div class="form-group"&ggt;>
                                            <label for="exampleInputPassword1">Mật khẩu mới</label>
                                            <input id="new_password-input" type="password" name="password"
                                                class="form-control" placeholder="Nhập mật khẩu mới...">
                                            <span id="new_password-error" class="display_error"
                                                style="color: red"></span>
                                        </div>
                                        <div class="form-group"&ggt;>
                                            <label for="exampleInputPassword1">Nhập lại mật khẩu mới</label>
                                            <input id="new_password_confirmation-input" name="password_confirmation"
                                                type="password" class="form-control" placeholder="Nhập lại mật khẩu...">
                                            <span id="new_password_confirmation-error" class="display_error"
                                                style="color: red"></span>
                                        </div>
                                        <button id="change-password-btn"
                                            data-href="
                                        @if (isAdmin() || isEAOStaff()) {{ route('admin.store_new_password') }}
                                        @elseif (isLecturer())
                                            {{ route('lecturer.store_new_password') }}
                                        @elseif (isStudent())
                                            {{ route('student.store_new_password') }} @endif
                                        "
                                            type="submit" class="btn btn-primary" onclick="return validate_form()">
                                            Đổi mật khẩu
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script type="text/javascript">
        //form validation
        function validate_form() {
            let check = true;

            let password = document.getElementById('new_password-input').value;
            if (password.length == 0) {
                document.getElementById('new_password-error').innerHTML = "Mật khẩu không được để trống";
                check = false;
            } else if (password.length <= 8) {
                document.getElementById('new_password-error').innerHTML = "Mật khẩu phải có độ dài hơn 8 ký tự";
                check = false;
            } else {
                document.getElementById('new_password-error').innerHTML = "";
            }

            let re_password = document.getElementById('new_password_confirmation-input').value;
            if (re_password.length == 0) {
                document.getElementById('new_password_confirmation-error').innerHTML = "Không được để trống";
                check = false;
            } else if (re_password != password) {
                document.getElementById('new_password_confirmation-error').innerHTML = "Mật khẩu không trùng khớp";
                check = false;
            } else {
                document.getElementById('new_password_confirmation-error').innerHTML = "";
            }
            if (check == false)
                return false;
            return true;
        }

        $(document).ready(function() {
            $('#change-password-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: $('#change-password-btn').data('href'),
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        $.toast({
                            heading: response.message,
                            showHideTransition: 'slide',
                            icon: 'success',
                            stack: false,
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
