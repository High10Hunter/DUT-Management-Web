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
                        <th rowspan="3" class="table-primary">Kết luận</th>
                    </tr>
                    <tr class="text-center table-borderless table-sm thead-dark">
                        @foreach ($periodsDate as $index => $date)
                            <th class="text-light">{{ $index + 1 }}</th>
                        @endforeach
                    </tr>
                    <tr class="text-center">
                        @foreach ($periodsDate as $index => $date)
                            <th class="text-primary font-weight-light table-warning">{{ $date }}</th>
                        @endforeach
                    </tr>
                    @foreach ($historyAttendances as $student)
                        <tr class="text-center">
                            <td>{{ $student->student_code }}</td>
                            <td>{{ $student->name }}</td>
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
                            <td>
                                @if ($student->not_attended_count + $student->late_count * 0.5 <= count($periodsDate) * 0.5)
                                    <i class="mdi mdi-check-bold text-success"></i>
                                @else
                                    <i class="dripicons-cross text-danger"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('lecturer.periods.form', ['moduleId' => $moduleId]) }}" class="mt-5">
            <button type="button" class="btn btn-outline-primary dripicons-arrow-thin-left">
                Quay lại
            </button>
        </a>
    </div>
@endsection
