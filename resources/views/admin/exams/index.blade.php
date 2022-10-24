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
                                        <th>Danh sách</th>
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
                                            <td>
                                                <button type="button" class="btn btn-secondary student-list"
                                                    data-toggle="modal" data-target="#student-list-modal"
                                                    data-module-id="{{ $each->id }}"
                                                    data-module-name="{{ $each->module->name }}"
                                                    data-module-date="{{ $each->date }}">
                                                    <i class="mdi mdi-format-list-bulleted-triangle"></i>
                                                </button>
                                            </td>
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

    <!-- Student list modal -->
    <div class="modal fade" id="student-list-modal" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-student-list-title" id="scrollableModalTitle">Danh sách sinh viên</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="students-in-module" class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <form action="{{ route('admin.exams.export_csv') }}" method="POST" class="form-group ml-2">
                        @csrf
                        <input type="hidden" name="module_id">
                        <input type="hidden" name="module_name">
                        <input type="hidden" name="module_date">
                        <button id="export-csv-btn" class="btn btn-primary">
                            <i class="mdi mdi-file-excel"></i> Xuất file Excel
                        </button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@push('js')
    <script>
        //prevent csrf-token miss-match
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getStudents() {
            $(".student-list").click(function() {
                let moduleId = $(this).data('module-id');
                let moduleName = $(this).data('module-name');
                let moduleDate = $(this).data('module-date');

                $("input[name='module_id']").val(moduleId);
                $("input[name='module_name']").val(moduleName);
                $("input[name='module_date']").val(moduleDate);

                $.ajax({
                    type: "POST",
                    url: '{{ route('admin.exams.get_students') }}',
                    data: {
                        'module_id': moduleId
                    },
                    dataType: "json",
                    success: function(response) {
                        $(".modal-student-list-title").text(moduleName);
                        let table =
                            `<table class="table table-hover table-centered mb-0">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>Mã SV</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Lớp</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        response.data.forEach(function(each) {
                            table +=
                                `
                                <tr class="text-center">
                                    <td>${each.student_code}</td>
                                    <td>${each.name}</td>
                                    <td>${each.email}</td>
                                    <td>${each.phone_number}</td>
                                    <td>${each.class.name}</td>
                                </tr>
                                `
                        });

                        table +=
                            `
                            </tbody>
                            </table>
                            `
                        $("#students-in-module").html(table);
                    }
                });
            });
        }


        $(document).ready(function() {
            getStudents();
        });
    </script>
@endpush
