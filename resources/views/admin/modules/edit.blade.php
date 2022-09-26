@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="form-title">Chỉnh sửa thông tin học phần</h4>
                    <p class="text-muted font-14">
                        Sửa đổi/cập nhật các thông tin sau
                    </p>

                    <form action="{{ route('admin.modules.update', ['module' => $module->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Giảng viên</label>
                                    <span id="name-error" class="display_error" style="color: red"></span>
                                    <select class="form-control select2" data-toggle="select2" name="lecturer_id">
                                        @foreach ($lecturers as $lecturer)
                                            <option value="{{ $lecturer->id }}"
                                                @if ($module->lecturer_id === $lecturer->id) selected @endif>
                                                {{ $lecturer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="faculty-select">Lịch học</label>
                                    <select class="select2 form-control select2-multiple" data-toggle="select2"
                                        multiple="multiple" name="schedule[]">
                                        @for ($i = 2; $i <= 7; $i++)
                                            <option value="{{ $i }}"
                                                @foreach ($schedule as $each)
                                              @if ((int) $each == $i)
                                              selected
                                              @endif @endforeach>
                                                {{ 'Thứ' . ' ' . $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="faculty-select">Số buổi học</label>
                                    <input type="number" name="lessons" class="form-control" value="{{ $lessons }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="example-select">Tiết bắt đầu</label>
                                    <select class="form-control" id="example-select" name="start_slot">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($module->start_slot === $i) selected @endif>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="example-select">Tiết kết thúc</label>
                                    <select class="form-control" id="example-select" name="end_slot">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($module->end_slot === $i) selected @endif>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Thời gian bắt đầu</label>
                                        <input class="form-control" id="example-date" type="date" name="begin_date"
                                            value="{{ $beginDate }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-right" onclick="return validate_form()">Cập
                            nhật</button>
                    </form>
                    <br>
                    <a href="{{ route('admin.modules.index') }}">
                        <button type="button" class="btn btn-outline-primary dripicons-arrow-thin-left">
                            Quay lại
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
