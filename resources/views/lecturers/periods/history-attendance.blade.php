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
                                @if ($student->not_attended_count + $student->late_count * 0.5 > count($periodsDate) * 0.5) table-danger
                                @elseif ($student->not_attended_count + $student->late_count * 0.5 > count($periodsDate) * 0.3) table-warning @endif
                                ">
                                {{ $student->name }}</td>
                            <td>{{ $student->class_name }}</td>
                            <td style="color:brown; font-weight:bold">
                                {{ $student->not_attended_count + $student->late_count * 0.5 }} /
                                {{ count($periodsDate) }}
                            </td>
                            @foreach ($student->attendances as $period)
                                @if ($period->pivot->status == 1)
                                    <td class="text-success font-weight-bold">.</td>
                                @elseif ($period->pivot->status == 0)
                                    <td class="text-danger font-weight-bold">N</td>
                                @elseif ($period->pivot->status == 2)
                                    <td class="text-primary font-weight-bold">P</td>
                                @elseif ($period->pivot->status == 3)
                                    <td class="text-warning font-weight-bold">M</td>
                                @endif
                            @endforeach
                            @if (count($periodsDate) == $module->lessons)
                                <td>
                                    @if ($student->not_attended_count + $student->late_count * 0.5 <= count($periodsDate) * 0.5)
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
