@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="form-title">Chỉnh sửa thông tin khoá</h4>
                    <p class="text-muted font-14">
                        Sửa đổi/cập nhật các thông tin sau
                    </p>

                    <form action="{{ route('admin.courses.update', ['course' => $course->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Tên khoá(*)</label>
                                    <span id="name-error" class="display_error" style="color: red"></span>
                                    <input type="text" id="name-input" name="name" class="form-control"
                                        value="{{ $course->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="begin_academic_year_input">Năm bắt đầu</label>
                                    <input class="form-control" id="begin_academic_year_input" type="date"
                                        name="begin_academic_year"
                                        @if ($course->begin_academic_year) value="{{ $course->begin_academic_year->format('Y-m-d') }}"> @endif
                                        </div>
                                    <div class="form-group">
                                        <label for="begin_academic_year_input">Năm kết thúc</label>
                                        <input class="form-control" id="end_academic_year_input" type="date"
                                            name="end_academic_year"
                                            @if ($course->end_academic_year) value="{{ $course->end_academic_year->format('Y-m-d') }}"> @endif
                                            </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" onclick="return validate_form()">Cập
                                    nhật</button>
                    </form>
                    <br>
                    <br>
                    <a href="{{ route('admin.courses.index') }}">
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

            if (check == false)
                return false;
            return true;
        }
    </script>
@endpush
