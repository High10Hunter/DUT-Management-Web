@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="form-title">Cập nhật sinh viên</h4>
                    <p class="text-muted font-14">
                        Điều chỉnh/Bổ sung thông tin
                    </p>
                    <form action="{{ route('admin.students.update', ['student' => $student->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Tên(*)</label>
                                    <span id="name-error" class="display_error" style="color: red"></span>
                                    <input type="text" id="name-input" name="name" value="{{ $student->name }}"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="birthday">Ngày sinh(*)</label>
                                    <span id="birthday-error" class="display_error" style="color: red"></span>
                                    <input class="form-control" id="birthday-input" type="date" name="birthday"
                                        value="{{ $student->birthday }}">
                                </div>
                                <div class="mt-3">
                                    <label>Giới tính(*)</label>
                                    <span id="gender-error" class="display_error" style="color: red"></span>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="genderMale" name="gender" class="custom-control-input"
                                            value="1" @if ($student->gender == 1) checked @endif>
                                        <label class="custom-control-label" for="genderMale">Nam</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="genderFemale" name="gender" class="custom-control-input"
                                            value="0" @if ($student->gender == 0) checked @endif>
                                        <label class="custom-control-label" for="genderFemale">Nữ</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mt-1">
                                    <label>Tình trạng(*)</label>
                                    <span id="status-error" class="display_error" style="color: red"></span>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="status" id="statusAvailable"
                                            class="custom-control-input" value="1"
                                            @if ($student->status == 1) checked @endif>
                                        <label class="custom-control-label" for="statusAvailable">Đi học</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="status" id="statusOff" class="custom-control-input"
                                            value="0" @if ($student->status == 0) checked @endif>
                                        <label class="custom-control-label" for="statusOff">Nghỉ học</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="status" id="statusReserved"
                                            class="custom-control-input" value="2"
                                            @if ($student->status == 2) checked @endif>
                                        <label class="custom-control-label" for="statusReserved">Bảo lưu</label>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="email">Email(*)</label>
                                    <span id="email-error" class="display_error" style="color: red"></span>
                                    <input type="email" id="email-input" name="email" class="form-control"
                                        placeholder="Email"
                                        @if ($student->email) value ="{{ $student->email }}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">Số điện thoại(*)</label>
                                    <span id="phone-number-error" class="display_error" style="color: red"></span>
                                    <input type="text" id="phone-number-input" name="phone_number" class="form-control"
                                        @if ($student->phone_number) value="{{ $student->phone_number }}" @endif>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right" onclick="return validate_form()">Cập
                            nhật</button>
                    </form>
                    <br>
                    <a href="{{ route('admin.students.index') }}">
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

            let status = document.querySelectorAll('input[name="status"]');
            let flag = false;
            for (let i = 0; i < status.length; i++) {
                if (status[i].checked == true) {
                    flag = true;
                    break;
                }
            }

            if (flag == true) {
                document.getElementById('status-error').innerHTML = "";
            } else {
                check = false;
                document.getElementById('status-error').innerHTML = "Vui lòng chọn tình trạng đi học";
            }

            let birthday = document.getElementById('birthday-input').value;
            if (!birthday) {
                document.getElementById('birthday-error').innerHTML = "Vui lòng chọn ngày sinh";
                check = false;
            } else {
                document.getElementById('birthday-error').innerHTML = "";
            }

            let email = document.getElementById('email-input').value;
            if (!email) {
                document.getElementById('email-error').innerHTML = "Vui lòng nhập email";
                check = false;

            } else {
                document.getElementById('email-error').innerHTML = "";
            }

            let phoneNumber = document.getElementById('phone-number-input').value;
            if (!phoneNumber) {
                document.getElementById('phone-number-error').innerHTML = "Vui lòng nhập số điện thoại";
                check = false;

            } else {
                document.getElementById('phone-number-error').innerHTML = "";
            }

            if (check == false)
                return false;
            return true;
        }
    </script>
@endpush
