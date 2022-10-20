@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý lịch thi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-8">
                            <a href="{{ route('admin.exams.index') }}">
                                <i class="mdi mdi-reload"> Tải lại</i>
                            </a>
                            <form id="form-filter" method="GET" class="form-inline">
                                <div class="form-group mb-2">
                                    <div class="input-group form-group">
                                        <input type="text" class="form-control" placeholder="Tìm lớp học phần..."
                                            aria-label="Recipient's username" name="q" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-lg-right">
                                <a href="{{ route('admin.exams.create') }}" type="button" class="btn btn-primary">
                                    <i class="mdi mdi-calendar-month"></i>
                                    Xếp lịch thi
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th>Tên lớp học phần</th>
                                        <th>Ngày thi</th>
                                        <th>Hình thức</th>
                                        <th>Tiết bắt đầu</th>
                                        <th>Giám thị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $each)
                                        <tr class="text-center">
                                            <td>{{ $each->module->name }}</td>
                                            <td>{{ $each->exam_date }}</td>
                                            <td>{{ $each->type_name }}</td>
                                            <td>{{ $each->start_slot }}</td>
                                            <td>{{ $each->proctor->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination pagination-rounded mb-0">
                                    {{ $data->links() }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
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

        $(document).ready(function() {

        });
    </script>
@endpush
