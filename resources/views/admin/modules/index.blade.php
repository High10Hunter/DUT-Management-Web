@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Phân công giảng dạy</h4>
            </div>
        </div>
    </div>

    {{-- success notification when adding or updating new module --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            {{ session()->forget('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-8">
                            <a href="{{ route('admin.modules.index') }}">
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
                        <div class="col-lg-2">
                            <div class="text-lg-right">
                                <button id="btn-create-module" type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#new-module-modal">
                                    <i class="mdi mdi-book-multiple"></i> Thêm học phần
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="text-lg-right">
                                <button type="button" class="btn btn-success md-2 mr-2" data-toggle="modal"
                                    data-target="#import-csv-modal">
                                    <i class="mdi mdi-file-table"></i> Tải lên học phần
                                </button>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>Tên lớp học phần</th>
                                    <th>Môn học</th>
                                    <th>Giảng viên</th>
                                    <th>Lịch học</th>
                                    <th>Tiết bắt đầu - Tiết kết thúc</th>
                                    <th>Thời gian bắt đầu bắt đầu</th>
                                    <th>Số buổi học</th>
                                    <th>Trạng thái</th>
                                    <th>Chỉnh sửa</th>
                                    <th>Danh sách sinh viên</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $each)
                                    <tr class="text-center">
                                        <td>{{ $each->name }}</td>
                                        <td>{{ $each->subject->name }}</td>
                                        <td>{{ $each->lecturer->name }}</td>
                                        <td>Thứ:
                                            @if (count($each->schedule) != 1)
                                                {{ implode($each->schedule, ',') }}
                                            @else
                                                {{ $each->schedule[0] }}
                                            @endif
                                        </td>
                                        <td>{{ $each->slot_range }}</td>
                                        <td>{{ $each->study_time }}</td>
                                        <td>{{ $each->lessons }}</td>
                                        <td>
                                            @if ($each->status === 1)
                                                <h4>
                                                    <span class="badge badge-success">
                                                        {{ $each->status_name }}
                                                    </span>
                                                </h4>
                                            @else
                                                <h4>
                                                    <span class="badge badge-danger">
                                                        {{ $each->status_name }}
                                                    </span>
                                                </h4>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.modules.edit', ['module' => $each->id]) }}">
                                                <button type="button" class="btn btn-info"><i class="mdi mdi-pen"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-secondary student-list"
                                                data-toggle="modal"
                                                @if ($each->status === 1) data-target="#student-list-modal"
                                                    @else   
                                                    data-target="#import-student-list-modal" @endif
                                                data-module-id="{{ $each->id }}"
                                                data-module-name="{{ $each->name . ' - ' . $each->subject->name }}"><i
                                                    class="mdi mdi-format-list-bulleted-triangle"></i>
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
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

    <!-- Create new module Modal -->
    <div id="new-module-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="new-module-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="new-module-modalLabel">Thêm mới học phần</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        Chọn môn
                        <select name="subject_id" class="select2 form-control subject-select" data-toggle="select2">.
                            <option value="" disabled selected></option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" data-id="{{ $subject->id }}" class="subject-option">
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        Chọn khoa
                        <select name="faculty_id" class="faculty-select form-control">
                            <option value="" disabled selected></option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}" class="faculty-option">
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                        Chọn giảng viên
                        <select name="lecturer_id" class="lecturer-select form-control">
                            <option value="" disabled selected></option>

                        </select>
                        Lịch học
                        <select class="select2 form-control select2-multiple schedule-select" data-toggle="select2"
                            multiple="multiple" name="schedule[]">
                            @for ($i = 2; $i <= 7; $i++)
                                <option value="{{ $i }}" class="schedule-option">
                                    {{ 'Thứ' . ' ' . $i }}
                                </option>
                            @endfor
                        </select>
                        Tiết bắt đầu
                        <select class="form-control start-slot-select">
                            <option value="" disabled selected></option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" class="start-slot-option">
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        Tiết kết thúc
                        <select class="form-control end-slot-select">
                            <option value="" disabled selected></option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" class="end-slot-option">
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        Ngày bắt đầu
                        <input type="date" class="form-control" id="start-date">
                        Số buổi học
                        <input type="number" class="form-control lesson-number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="btn-new-module">Thêm</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Đóng</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Import CSV Modal -->
    <div id="import-csv-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="import-csv-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="import-csv-modalLabel">Tải lên file CSV</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p>Chọn file CSV để tải lên</p>
                        <input type="file" name="csv" id="csv"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                    </div>
                    <div class="form-group">
                        <a href="{{ route('admin.modules.export_sample_csv') }}">
                            <em>Tải file mẫu</em></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn-import-csv">Tải lên</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Đóng</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Import Student List CSV Modal -->
    <div id="import-student-list-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="import-csv-list-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="import-csv-list-modalLabel">Tải lên danh sách sinh viên </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p>Chọn file CSV để tải lên</p>
                        <input type="file" name="student-list-csv" id="student-list-csv"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                    </div>
                    <div class="form-group">
                        <a href="{{ route('admin.modules.export_sample_student_list_csv') }}">
                            <em>Tải file mẫu</em></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn-import-student-list-csv">Tải lên</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Đóng</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Student list modal -->
    <div class="modal fade" id="student-list-modal" tabindex="-1" role="dialog"
        aria-labelledby="scrollableModalTitle" aria-hidden="true">
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
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @push('js')
        <script>
            //prevent csrf-token miss-match
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function getLecturers() {
                $(".faculty-select").change(function() {
                    let faculty_id = $("option[class=faculty-option]:selected").val();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.modules.get_lecturers') }}",
                        data: {
                            'faculty_id': faculty_id
                        },
                        dataType: "json",
                        success: function(response) {
                            $('.lecturer-select').empty();
                            response.data.forEach(each => {
                                let lecturerId = each.id;
                                let lecturerName = each.name;
                                $('.lecturer-select').append(
                                    `<option value="${lecturerId}" class="lecturer-option">${lecturerName}</option>`
                                )
                            });
                        }
                    });
                });
            }

            function getStudents() {
                $(".student-list").click(function() {
                    let moduleId = $(this).data('module-id');
                    let moduleName = $(this).data('module-name');
                    $.ajax({
                        type: "POST",
                        url: '{{ route('admin.modules.get_students') }}',
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
                                console.log(each);
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
                $(".select-filter").change(function(e) {
                    $("#form-filter").submit();
                });

                getStudents();

                //csv import
                $("#btn-import-csv").click(function() {
                    let formData = new FormData();
                    formData.append("file", $("#csv")[0].files[0]);

                    $(this).prop('disabled', true);
                    $(this).html("<span role='btn-status'></span>Đang tải lên");
                    $("span[role='btn-status']").attr("class", "spinner-border spinner-border-sm mr-1");


                    $.ajax({
                        type: 'POST',
                        url: '{{ route('admin.modules.import_csv') }}',
                        cache: false,
                        // async: false,
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                        success: function(response) {
                            $.toast({
                                heading: 'Thành công',
                                text: response.message,
                                showHideTransition: 'slide',
                                position: 'bottom-left',
                                icon: 'success'
                            });
                            $("#import-csv-modal").modal('hide');
                        },
                        error: function(response) {
                            $('#btn-import-csv').prop('disabled', false);
                            $("span[role='btn-status']").remove();
                            $('#btn-import-csv').html('Tải lên');
                            $.toast({
                                heading: 'Thất bại',
                                text: "Không thể tải lên, vui lòng kiểm tra lại file",
                                showHideTransition: 'fade',
                                icon: 'error'
                            })
                        }
                    });
                });

                //csv import student list
                let moduleId;
                $(".student-list").click(function() {
                    moduleId = $(this).data('module-id');
                });
                $("#btn-import-student-list-csv").click(function() {
                    let formData = new FormData();
                    formData.append("file", $("#student-list-csv")[0].files[0]);
                    formData.append("module_id", moduleId);

                    $(this).prop('disabled', true);
                    $(this).html("<span role='btn-status'></span>Đang tải lên");
                    $("span[role='btn-status']").attr("class", "spinner-border spinner-border-sm mr-1");

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('admin.modules.import_student_list_csv') }}',
                        cache: false,
                        // async: false,
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                        success: function(response) {
                            $.toast({
                                heading: 'Thành công',
                                text: response.message,
                                showHideTransition: 'slide',
                                position: 'bottom-left',
                                icon: 'success'
                            });
                            $("#import-student-list-modal").modal('hide');
                        },
                        error: function(response) {
                            $('#btn-import-student-list-csv').prop('disabled', false);
                            $("span[role='btn-status']").remove();
                            $('#btn-import-student-list-csv').html('Tải lên');
                            $.toast({
                                heading: 'Thất bại',
                                text: "Không thể tải lên, vui lòng kiểm tra lại file",
                                showHideTransition: 'fade',
                                icon: 'error'
                            })
                        }
                    });
                });

                $("#btn-create-module").click(function() {
                    getLecturers();
                    $("#btn-new-module").click(function() {
                        let subjectId = $('.subject-select').find(":selected").val();
                        let lecturerId = $('.lecturer-select').find(":selected").val();
                        let schedule = $('.schedule-select').select2("val");
                        let startSlot = $('.start-slot-select').find(":selected").val();
                        let endSlot = $('.end-slot-select').find(":selected").val();
                        let startDate = $('#start-date').val();
                        let lessons = $(".lesson-number").val();

                        $(this).prop('disabled', true);
                        $(this).html("<span role='btn-status'></span>Đang tải lên");
                        $("span[role='btn-status']").attr("class",
                            "spinner-border spinner-border-sm mr-1");


                        $.ajax({
                            type: "POST",
                            url: '{{ route('admin.modules.store') }}',
                            data: {
                                'subject_id': subjectId,
                                'lecturer_id': lecturerId,
                                'schedule': JSON.stringify(schedule),
                                'start_slot': startSlot,
                                'end_slot': endSlot,
                                'begin_date': startDate,
                                'lessons': lessons,
                            },
                            success: function(response) {

                                $.toast({
                                    heading: 'Thành công',
                                    text: response.message,
                                    showHideTransition: 'slide',
                                    position: 'bottom-left',
                                    icon: 'success'
                                });
                                $("#new-module-modal").modal('hide');
                            },
                            error: function(response) {
                                $('#btn-new-module').prop('disabled', false);
                                $("span[role='btn-status']").remove();
                                $('#btn-new-module').html('Thêm');
                                $.toast({
                                    heading: 'Thất bại',
                                    text: response.responseJSON.message,
                                    showHideTransition: 'fade',
                                    icon: 'error'
                                })
                            }
                        });
                    })
                });
            });
        </script>
    @endpush
@endsection
