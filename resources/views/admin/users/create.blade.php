@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="form-title">Thêm mới người dùng</h4>
                    <p class="text-muted font-14">
                        Điền đầy đủ các thông tin sau
                    </p>
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Tên(*)</label>
                                    <span id="name-error" class="display_error" style="color: red"></span>
                                    <input type="text" id="name-input" name="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="birthday">Ngày sinh(*)</label>
                                    <span id="birthday-error" class="display_error" style="color: red"></span>
                                    <input class="form-control" id="birthday-input" type="date" name="birthday">
                                </div>
                                <div class="mt-3">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Số điện thoại</label>
                                        <input type="text" id="phone_number" name="phone_number" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="avatar">Ảnh đại diện</label>
                                        <input type="file" id="avatar" name="avatar" class="form-control-file">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label>Giới tính(*)</label>
                                <span id="gender-error" class="display_error" style="color: red"></span>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="genderMale" name="gender" class="custom-control-input"
                                        value="1">
                                    <label class="custom-control-label" for="genderMale">Nam</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="genderFemale" name="gender" class="custom-control-input"
                                        value="0">
                                    <label class="custom-control-label" for="genderFemale">Nữ</label>
                                </div>
                                <br>
                                <label for="role-select">Vai trò(*)</label>
                                <span id="role-error" class="display_error" style="color: red"></span>
                                <select id="role-select" name="role" class="custom-select mb-3">
                                    <option selected></option>
                                    @foreach ($roles as $role => $value)
                                        <option value="{{ $value }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                                <label for="faculty-select">Khoa</label>
                                <select id="faculty-select" name="faculty" class="custom-select mb-3">
                                    <option selected value=""></option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                    @endforeach
                                </select>
                                <label for="class-select">Lớp</label>
                                <select id="class-select" name="class" class="custom-select mb-3">
                                    <option selected value=""></option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right"
                            onclick="return validate_form()">Thêm</button>
                    </form>
                    <br>
                    <a href="{{ route('admin.users.index') }}">
                        <button type="button" class="btn btn-outline-primary dripicons-arrow-thin-left">
                            Quay lại
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function validate_form() {
            let check = true;
            let name = document.getElementById('name-input').value;
            if (name.length == 0) {
                document.getElementById('name-error').innerHTML = "Vui lòng nhập tên";
                check = false;
            } else {
                document.getElementById('name-error').innerHTML = "";
            }

            let gender = document.querySelectorAll('input[name="gender"]');
            let flag = false;
            for (let i = 0; i < gender.length; i++) {
                if (gender[i].checked == true) {
                    flag = true;
                    break;
                }
            }
            if (flag == true) {
                document.getElementById('gender-error').innerHTML = "";
            } else {
                check = false;
                document.getElementById('gender-error').innerHTML = "Vui lòng chọn giới tính";
            }

            let birthday = document.getElementById('birthday-input').value;
            if (!birthday) {
                document.getElementById('birthday-error').innerHTML = "Vui lòng chọn ngày sinh";
                check = false;
            } else {
                document.getElementById('birthday-error').innerHTML = "";
            }

            let role = document.getElementById('role-select').value;
            if (!role) {
                document.getElementById('role-error').innerHTML = "Vui lòng chọn vai trò";
                check = false;
            } else {
                document.getElementById('role-error').innerHTML = "";
            }

            if (check == false)
                return false;
            return true;
        }
    </script>
@endpush
