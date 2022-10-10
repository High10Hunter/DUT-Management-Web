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
                    <tr class="text-center">
                        <th>Sinh viên</th>
                        @foreach ($periodsDate as $date)
                            <th>{{ $date }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historyAttendance as $student => $periods)
                        <tr class="text-center">
                            <td>{{ $student }}</td>
                            @foreach ($periods as $period)
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
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
