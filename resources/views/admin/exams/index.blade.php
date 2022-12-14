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
                                        <th>Kiểu thi</th>
                                        <th>Tiết bắt đầu</th>
                                        <th>Giám thị</th>
                                        <th>Danh sách</th>
                                        <th>Chỉnh sửa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $exams)
                                        <tr class="text-center">
                                            @if (count($exams) == 1)
                                                <td>{{ $exams[0]->module->name . ' - ' . $exams[0]->module->subject->name }}
                                                </td>
                                                <td>{{ $exams[0]->exam_date }}</td>
                                                <td>{{ $exams[0]->type_name }}</td>
                                                <td>Thi riêng</td>
                                                <td>{{ $exams[0]->start_slot }}</td>
                                                <td>{{ $exams[0]->proctor->name }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-secondary student-list"
                                                        data-toggle="modal" data-target="#student-list-modal"
                                                        data-module-id="{{ $exams[0]->id }}"
                                                        data-module-name="{{ $exams[0]->module->name }}"
                                                        data-module-date="{{ $exams[0]->date }}">
                                                        <i class="mdi mdi-format-list-bulleted-triangle"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info trigger-modal-btn"
                                                        data-toggle="modal" data-target="#edit-exam-modal"
                                                        data-exam-id="{{ $exams[0]->id }}"
                                                        data-exam-date="{{ $exams[0]->date }}"
                                                        data-exam-type="{{ $exams[0]->type }}"
                                                        data-start-slot="{{ $exams[0]->start_slot }}"
                                                        data-proctor-id="{{ $exams[0]->proctor_id }}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                </td>
                                            @else
                                                <td>
                                                    @for ($i = 0; $i < count($exams); $i++)
                                                        @if ($i != count($exams) - 1)
                                                            {{ $exams[$i]->module->name . ', ' }}
                                                        @else
                                                            {{ $exams[$i]->module->name . ' - ' }}
                                                        @endif
                                                    @endfor
                                                    {{ $exams[0]->module->subject->name }}
                                                </td>
                                                <td>{{ $exams[0]->exam_date }}</td>
                                                <td>{{ $exams[0]->type_name }}</td>
                                                <td>Thi chung</td>
                                                <td>{{ $exams[0]->start_slot }}</td>
                                                <td>{{ $exams[0]->proctor->name }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-secondary student-list"
                                                        data-toggle="modal" data-target="#student-list-modal"
                                                        data-module-id="{{ $exams[0]->id }}"
                                                        data-module-name="
                                                        @for ($i = 0; $i < count($exams) ; $i++)
                                                        @if ($i != count($exams) - 1)
                                                            {{ $exams[$i]->module->name . ', ' }}
                                                        @else
                                                            {{ $exams[$i]->module->name }}
                                                        @endif @endfor"
                                                        data-module-date="{{ $exams[0]->date }}">
                                                        <i class="mdi mdi-format-list-bulleted-triangle"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info trigger-modal-btn"
                                                        data-toggle="modal" data-target="#edit-exam-modal"
                                                        data-exam-id="{{ $exams[0]->id }}"
                                                        data-exam-date="{{ $exams[0]->date }}"
                                                        data-exam-type="{{ $exams[0]->type }}"
                                                        data-start-slot="{{ $exams[0]->start_slot }}"
                                                        data-proctor-id="{{ $exams[0]->proctor_id }}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                </td>
                                            @endif
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


    <!-- Edit exam modal -->
    <div id="edit-exam-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Chỉnh sửa lịch thi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="edit-exam-schedule" method="POST">
                        @csrf
                        <input type="hidden" name="exam_id">
                        Ngày thi
                        <input type="date" name="edit_date" class="form-control">
                        Hình thức
                        <select name="edit_type" class="form-control">
                            <option value="" selected disabled></option>
                            <option value="0">Lý thuyết</option>
                            <option value="1">Thực hành</option>
                        </select>
                        Tiết bắt đầu
                        <select name="edit_start_slot" class="form-control">
                            <option value="" selected disabled></option>
                            @for ($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}">
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        Giám thị
                        <select name="edit_proctor_id" class="form-control">
                            <option value="" selected disabled></option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">
                                    {{ $lecturer->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Đóng</button>
                            <button type="submit" id="edit-exam-btn" class="btn btn-primary">Thay đổi</button>
                        </div>
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

                //remove blank spaces and '\n'
                let moduleName = $(this).data('module-name');
                moduleName = $(this).data('module-name');
                moduleName = moduleName.split(" ").join("");
                moduleName = moduleName.split("\n").join("");

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

            $('.trigger-modal-btn').click(function() {
                let examId = $(this).data('exam-id');
                let examDate = $(this).data('exam-date');
                let examType = $(this).data('exam-type');
                let startSlot = $(this).data('start-slot');
                let proctorId = $(this).data('proctor-id');

                $("input[name='exam_id']").val(examId);
                $("input[name='edit_date']").val(examDate);
                $("select[name='edit_type']").val(examType);
                $("select[name='edit_start_slot']").val(startSlot);
                $("select[name='edit_proctor_id']").val(proctorId);
            });

            $('#edit-exam-schedule').submit(function(event) {
                event.preventDefault();

                $('#edit-exam-btn').prop('disabled', true);
                $('#edit-exam-btn').html("<span role='btn-status'></span>Đang cập nhật");
                $("span[role='btn-status']").attr("class", "spinner-border spinner-border-sm mr-1");

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.exams.update') }}",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        $('#edit-exam-modal').modal('hide');

                        //reset edit button
                        $('#edit-exam-btn').prop('disabled', false);
                        $("span[role='btn-status']").remove();
                        $('#edit-exam-btn').html('Thay đổi');

                        $.toast({
                            heading: response.message,
                            showHideTransition: 'slide',
                            icon: 'success',
                            stack: false,
                        });
                    },
                    error: function(response) {
                        $('#edit-exam-btn').prop('disabled', false);
                        $("span[role='btn-status']").remove();
                        $('#edit-exam-btn').html('Tạo lịch thi');

                        $.toast({
                            heading: "Không cập nhật được lịch thi",
                            showHideTransition: 'slide',
                            icon: 'error',
                            stack: false,
                        });
                    }
                });
            });
        });
    </script>
@endpush
