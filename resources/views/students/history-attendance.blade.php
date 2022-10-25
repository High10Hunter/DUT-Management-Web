@extends('student_layout.master')
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
    <h4>Lịch sử đi học</h4>
    <div class="row">
        <div class="col-lg-4">
            <p class="text-muted font-14">Chọn lớp học phần</p>
            <form action="{{ route('student.learning_schedule.history_attendance') }}" id="form-filter" class="form-inline">
                <select name="module_id" class="form-control select-filter select2" data-toggle="select2">
                    <option value="" disabled selected></option>
                    @foreach ($modules as $module)
                        <option value="{{ $module->id }}" @if (isset($moduleId) && $moduleId == $module->id) selected @endif>
                            {{ $module->name . ' - ' . $module->subject->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            @isset($moduleId)
                <div class="row ml-2 mt-2 lessons-count" data-module-lessons="{{ $moduleLessons }}">
                    Số buổi đã dạy : <div id="teached-lessons" class="text-black col-lg-2">{{ count($periodsDate) }}</div>
                    Số buổi còn lại : <div id="remaining-lessons" class="text-black col-lg-2">
                        {{ $moduleLessons - count($periodsDate) }}</div>
                </div>
            @endisset
        </div>
    </div>

    @isset($historyAttendances)
        <dl class="row mb-1">
            <div class="col-lg-8 mt-3">
                <ul>
                    <li>
                        Đi muộn = <strong class="text-secondary"> vắng {{ $configs['late_coefficient'] }} buổi</strong>
                    </li>
                    <li>
                        Số buổi phép tối đa: <strong class="text-primary"> {{ $configs['max_excused'] }} buổi</strong>
                    </li>
                    <li>
                        Sinh viên vắng <strong class="text-danger"> quá {{ $configs['exam_ban_coefficient'] * 100 }}% số buổi
                            học </strong> sẽ không được thi
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
        </dl>
        <br>
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
            </div>
            @if (count($periodsDate) < $module->lessons)
                <div class="mt-2">
                    <p class="text-dark">
                        * Số buổi còn lại có thể nghỉ:
                        <strong class="text-danger">
                            {{ getRemainingAbsentDays(
                                $historyAttendances[0]->not_attended_count,
                                $historyAttendances[0]->late_count,
                                $configs['late_coefficient'],
                                $configs['exam_ban_coefficient'],
                                $moduleLessons,
                                count($periodsDate),
                            ) }}
                            buổi
                        </strong>
                        <br>
                        * Số buổi còn lại có thể đi muộn:
                        <strong style="color:rgb(201, 201, 56)">
                            {{ getRemainingAbsentDays(
                                $historyAttendances[0]->not_attended_count,
                                $historyAttendances[0]->late_count,
                                $configs['late_coefficient'],
                                $configs['exam_ban_coefficient'],
                                $moduleLessons,
                                count($periodsDate),
                            ) / $configs['late_coefficient'] }}
                            buổi
                        </strong>
                    </p>
                </div>
            @endif
        </div>
    @endisset
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $(".select-filter").change(function(e) {
                $("#form-filter").submit();
            });
        });
    </script>
@endpush
