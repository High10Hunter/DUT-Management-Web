@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="form-title">Chỉnh sửa thông tin chuyên ngành</h4>
                    <p class="text-muted font-14">
                        Sửa đổi/cập nhật các thông tin sau
                    </p>

                    <form action="{{ route('admin.majors.update', ['major' => $major->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Tên ngành(*)</label>
                                    <span id="name-error" class="display_error" style="color: red"></span>
                                    <input type="text" id="name-input" name="name" class="form-control"
                                        value="{{ $major->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="faculty-select">Khoa(*)</label>
                                    <select id="faculty-select" name="faculty_id" class="select2 mb-3"
                                        data-toggle="select2">
                                        @foreach ($faculties as $faculty)
                                            <option value="{{ $faculty->id }}"
                                                @if ($major->faculty_id == $faculty->id) selected @endif>
                                                {{ $faculty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" onclick="return validate_form()">Cập
                                    nhật</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <a href="{{ route('admin.majors.index') }}">
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
