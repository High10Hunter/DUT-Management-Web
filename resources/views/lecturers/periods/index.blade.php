@extends('lecturer_layout.master')
@push('css')
    {{-- scrollable table --}}
    <style>
        .my-custom-scrollbar {
            position: relative;
            height: 500px;
            overflow: auto;
        }

        .table-wrapper-scroll-y {
            display: block;
        }
    </style>
@endpush
@section('content')
    <h4>Điểm danh</h4>
    <div class="tab-content">
        <div class="tab-pane show active">
            <div class="row">
                <div class="col-lg-4">
                    <p class="text-muted font-14">Chọn lớp học phần</p>
                    <form action="{{ route('lecturer.periods.form') }}" id="form-filter" method="POST" class="form-inline">
                        @csrf
                        <select name="module_id" class="form-control select-filter select2" data-toggle="select2">
                            <option value="" disabled selected></option>
                            @foreach ($modules as $module)
                                <option value="{{ $module->id }}" @if (isset($moduleId) && $moduleId == $module->id) selected @endif>
                                    {{ $module->name . ' - ' . $module->subject->name }}
                                    @if (in_array($currentWeekday + 1, $module->schedule))
                                        (Học phần trong ngày)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    @isset($students)
        @if (isset($attendance))
            <h3 class="text-center">
                <span id="class-status" class="badge badge-success">Đã điểm danh</span>
            </h3>
        @else
            <h3 class="text-center">
                <span id="class-status" class="badge badge-danger">Chưa điểm danh</span>
            </h3>
        @endif
        <input type="hidden" name="module_id" value="{{ $moduleId }}">
        <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
            <table id="module-students-list" class="table table-hover table-centered mb-0">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th>Mã SV</th>
                        <th>Tên</th>
                        <th>Giới tính</th>
                        <th>Lớp</th>
                        <th>Tình trạng đi học</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr class="text-center">
                            <td>{{ $student->student_code }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->gender_name }}</td>
                            <td>
                                @if (optional($student->class)->name != null)
                                    {{ optional($student->class)->name }}
                                @else
                                    <i class="dripicons-wrong"></i>
                                @endif
                            </td>
                            <td>
                                <div class="status-check mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input value="1" type="radio" id="{{ $student->id . 'available' }}"
                                            name="status{{ $student->id }}" class="custom-control-input"
                                            @if (optional($student->attendance)->status === 1) checked @endif>
                                        <label class="custom-control-label" for="{{ $student->id . 'available' }}">
                                            Đi học
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input value="0" type="radio" id="{{ $student->id . 'off' }}"
                                            name="status{{ $student->id }}" class="custom-control-input"
                                            @if (optional($student->attendance)->status === 0) checked @endif>
                                        <label class="custom-control-label" for="{{ $student->id . 'off' }}">
                                            Vắng
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input value="2" type="radio" id="{{ $student->id . 'excuse' }}"
                                            name="status{{ $student->id }}" class="custom-control-input"
                                            @if (optional($student->attendance)->status === 2) checked @endif>
                                        <label class="custom-control-label" for="{{ $student->id . 'excuse' }}">
                                            Có phép
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input value="3" type="radio" id="{{ $student->id . 'late' }}"
                                            name="status{{ $student->id }}" class="custom-control-input"
                                            @if (optional($student->attendance)->status === 3) checked @endif>
                                        <label class="custom-control-label" for="{{ $student->id . 'late' }}">
                                            Đi muộn
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <button type="button" id="attendance-btn" class="btn btn-info btn-rounded float-right mr-5">
                <i class="mdi mdi-account-check"></i> Điểm danh
            </button>
        </div>
    @endisset
@endsection
@push('js')
    <script>
        //prevent csrf-token miss-match
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            //filter module
            $(".select-filter").change(function(e) {
                $("#form-filter").submit();
            });
            //check attendance
            $("#attendance-btn").click(function(e) {
                let statusArr = {};
                $('input[type="radio"]:checked').each(function() {
                    let name = $(this).attr('name');
                    let studentId = name.slice(6); //status

                    let status = $(this).val();
                    statusArr[studentId] = status;
                });

                let moduleId = $("input[name='module_id']").val();

                $(this).prop('disabled', true);
                $(this).html("<span role='btn-status'></span>Đang cập nhật");
                $("span[role='btn-status']").attr("class", "spinner-border spinner-border-sm mr-1");

                $.ajax({
                    type: 'POST',
                    url: '{{ route('lecturer.periods.attendance') }}',
                    data: {
                        'module_id': moduleId,
                        'status': statusArr,
                    },
                    success: function(response) {
                        $.toast({
                            heading: 'Thành công',
                            text: 'Đã cập nhật điểm danh',
                            showHideTransition: 'slide',
                            position: 'bottom-left',
                            icon: 'success'
                        });
                        $("#attendance-btn").prop('disabled', false);
                        $("#attendance-btn").html(
                            '<i class="mdi mdi-account-check"></i> Điểm danh');
                        $("span[role='btn-status']").remove();

                        $("#class-status").html('Đã điểm danh');
                        $("#class-status").attr('class', 'badge badge-success');
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Thất bại',
                            text: 'Không thể điểm danh, vui lòng chọn trạng thái đi học của sinh viên !',
                            showHideTransition: 'fade',
                            position: 'bottom-left',
                            icon: 'error'
                        })
                        $("#attendance-btn").prop('disabled', false);
                        $("#attendance-btn").html(
                            '<i class="mdi mdi-account-check"></i> Điểm danh');
                        $("span[role='btn-status']").remove();
                    }
                });
            });
        });
    </script>
@endpush
