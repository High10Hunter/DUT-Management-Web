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

        .table.table-hover-cells.table-bordered tr:hover {
            background: none;
        }

        td:hover {
            background: lightgray;
        }
    </style>
@endpush
@section('content')
    <h4>Lịch sử điểm danh</h4>
    <dl class="row mb-1">
        <dt class="col-sm-1">Học phần:</dt>
        <dd class="col-sm-6 ">
            {{ $module->name }}
        </dd>

        <div class="col-lg-8 mt-3">
            <ul>
                <li>
                    Đi muộn = <strong class="text-secondary"> vắng 0.5 buổi</strong>
                </li>
                <li>
                    Số buổi phép tối đa: <strong class="text-primary"> 3 buổi</strong>
                </li>
                <li>
                    Sinh viên vắng <strong class="text-danger"> quá 50% số buổi học </strong> sẽ không được thi
                    kết
                    thúc học
                    phần
                </li>
            </ul>
        </div>

        <em class="pl-5">Ký hiệu:</em>
        <div class="row float-right">
            <div class="col-lg-12">
                <div class="pl-3 text-success font-weight-bold">. : Đi học</div>
                <div class="pl-3 text-danger font-weight-bold">N : Vắng</div>
                <div class="pl-3 text-primary font-weight-bold">P : Có phép</div>
                <div class="pl-3 text-warning font-weight-bold">M : Đi muộn</div>
                <div class="pl-3 text-info">
                    <i class="mdi mdi-check-bold text-success"></i> : Được phép thi
                </div>
                <div class="pl-3 text-danger">
                    <i class="dripicons-cross text-danger"></i> : Học lại
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('lecturer.periods.history_attendance', ['moduleId' => $module->id]) }}">
                <a href="{{ route('lecturer.periods.history_attendance', ['moduleId' => $module->id]) }}">
                    <i class="mdi mdi-reload"> Tải lại</i>
                </a>
                <div class="form-group">
                    <div class="input-group w-25">
                        <input type="hidden" name="module_id" value="{{ $module->id }}">
                        <input name="q" type="text" class="form-control" placeholder="Tìm kiếm sinh viên..."
                            value="{{ $search }}">
                        <div class="input-group-append">
                            <button class="btn btn-dark">Tìm</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </dl>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered table-hover-cells table-centered mb-0">
                <thead>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <th rowspan="3" class="table-primary">Mã SV</th>
                        <th rowspan="3" class="table-primary">Sinh viên</th>
                        <th rowspan="3" class="table-primary">Lớp</th>
                        <th rowspan="3" class="table-primary">Số buổi nghỉ</th>
                        <th colspan="{{ count($periodsDate) }}" class="table-borderless table-info">Buổi</th>
                        @if (count($periodsDate) == $module->lessons)
                            <th rowspan="3" class="table-primary">Kết luận</th>
                        @endif
                    </tr>
                    <tr class="text-center table-borderless table-sm thead-dark">
                        @foreach ($periodsDate as $index => $date)
                            <th class="text-light">{{ $index + 1 }}</th>
                        @endforeach
                    </tr>
                    <tr class="text-center">
                        @foreach ($periodsDate as $index => $date)
                            <th class="text-dark bg-warning">{{ $date }}</th>
                        @endforeach
                    </tr>
                    @foreach ($historyAttendances as $student)
                        <tr class="text-center">
                            <td>{{ $student->student_code }}</td>
                            <td
                                class=" 
                                @if (checkBanFromExam(
                                    $student->not_attended_count,
                                    $student->late_count,
                                    $configs['late_coefficient'],
                                    count($periodsDate),
                                    $configs['exam_ban_coefficient'])) table-danger
                                @elseif (checkWarningExam(
                                    $student->not_attended_count,
                                    $student->late_count,
                                    $configs['late_coefficient'],
                                    count($periodsDate),
                                    $configs['exam_warning_coefficient'])) table-warning @endif
                                ">
                                {{ $student->name }}</td>
                            <td>{{ $student->class_name }}</td>
                            <td name="{{ $student->id }}" style="color:brown; font-weight:bold">
                                {{ getTotalAbsentLessons($student->not_attended_count, $student->late_count, $configs['late_coefficient']) }}
                                /
                                {{ count($periodsDate) }}
                            </td>
                            @foreach ($student->attendances as $period)
                                @if ($period->pivot->status == 1)
                                    <td class="status text-success font-weight-bold" data-period-id="{{ $period->id }}"
                                        data-student-id="{{ $student->id }}" data-status=".">
                                        .
                                    </td>
                                @elseif ($period->pivot->status == 0)
                                    <td class="status text-danger font-weight-bold" data-period-id="{{ $period->id }}"
                                        data-student-id="{{ $student->id }}" data-status="N">
                                        N
                                    </td>
                                @elseif ($period->pivot->status == 2)
                                    <td class="status text-primary font-weight-bold" data-period-id="P"
                                        data-student-id="{{ $student->id }}" data-status="P">
                                        P
                                    </td>
                                @elseif ($period->pivot->status == 3)
                                    <td class="status text-warning font-weight-bold" data-period-id="{{ $period->id }}"
                                        data-student-id="{{ $student->id }}" data-status="M">
                                        M
                                    </td>
                                @endif
                            @endforeach
                            @if (count($periodsDate) == $module->lessons)
                                <td>
                                    @if (getTotalAbsentLessons($student->not_attended_count, $student->late_count, $configs['late_coefficient']) <=
                                        count($periodsDate) * $configs['exam_ban_coefficient'])
                                        <i class="mdi mdi-check-bold text-success"></i>
                                    @else
                                        <i class="dripicons-cross text-danger"></i>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('lecturer.periods.form', ['moduleId' => $module->id]) }}">
                <button type="button" class="btn btn-outline-primary dripicons-arrow-thin-left mt-3 mb-5">
                    Quay lại
                </button>
            </a>
        </div>
    </div>
@endsection
@push('js')
    <script>
        //prevent csrf-token miss-match
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function updateStatusAttendanceHistory() {
            let periodId = null;
            let studentId = null;
            let currentStatus = null;
            let selectedStatus = null;

            //trigger select option status
            $(".status").dblclick(function() {
                selectOptions = `
                    <select class="form-control select-status">
                        <option value="" selected disabled></option>"
                        <option value="1">Đi học</option>
                        <option value="0">Vắng</option>
                        <option value="2">Có phép</option>
                        <option value="3">Muộn</option>
                        </select>`
                $(this).html(selectOptions);

                periodId = $(this).data('period-id');
                studentId = $(this).data('student-id');
                currentStatus = $(this).data('status');
            });

            //determine the click event
            $(document).click(function(event) {
                let target = event.target;
                //click outside the select option
                if (target.closest('.select-status') == null &&
                    $('.select-status').is(":visible")) {
                    selectedStatus = $('.select-status option:selected').val();
                    let oldHistoryStatus = $('.select-status').parents('td')
                    $('.select-status').remove();

                    if (selectedStatus != "") {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('lecturer.periods.update_history_attendance') }}',
                            data: {
                                'period_id': periodId,
                                'student_id': studentId,
                                'status': selectedStatus,
                            },
                            dataType: "json",
                            success: function(response) {
                                $.toast({
                                    heading: 'Thành công',
                                    text: response.message,
                                    showHideTransition: 'slide',
                                    position: 'bottom-left',
                                    icon: 'success'
                                });

                                oldHistoryStatus.attr({
                                    "class": `status ${response.data[0].displayClass} font-weight-bold`,
                                    "data-status": response.data[0].text,
                                });
                                oldHistoryStatus.text(response.data[0].text);
                            }
                        });
                    } else {
                        oldHistoryStatus.text(currentStatus);
                    }

                    removeClickListener();
                }
            });

            document.addEventListener('click', updateStatusAttendanceHistory);
        }

        const removeClickListener = () => {
            document.removeEventListener('click', updateStatusAttendanceHistory);
        }

        $(document).ready(function() {
            updateStatusAttendanceHistory();
        });
    </script>
@endpush
