@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý sinh viên</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            {{-- success notification when adding or updating new staff --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    {{ session()->forget('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body container-fluid">
                    <div class="row mb-2">
                        <div class="col-lg-8">
                            <a href="{{ route('admin.students.index') }}">
                                <i class="mdi mdi-reload"> Tải lại</i>
                            </a>
                            <form id="form-filter" method="GET" class="form-inline">
                                <div class="form-group">
                                    <div class="input-group form-group mx-sm-1">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm sinh viên..."
                                            aria-label="Recipient's username" name="q" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mx-sm-1 mb-3">
                                    <label for="course-select" class="mr-2">Khoá</label>
                                    <select class="select2 select-filter" data-toggle="select2" name="course_id">
                                        <option value="" selected>Tất cả</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}"
                                                @if ((string) $course->id == $selectedCourse) selected @endif>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mx-sm-1 mb-3">
                                    <label for="major-select" class="mr-2">Ngành</label>
                                    <select id="major_select" class="select2 select-filter" data-toggle="select2"
                                        name="major_id">
                                        <option value="" selected>Tất cả</option>
                                        @foreach ($majors as $major)
                                            <option value="{{ $major->id }}"
                                                @if ((string) $major->id == $selectedMajor) selected @endif>
                                                {{ $major->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mx-sm-1 mb-3">
                                    <label for="major-select" class="mr-2">Lớp</label>
                                    <select id="class-select" class="select2 select-filter" data-toggle="select2"
                                        name="class_id">
                                        <option value="" selected>Tất cả</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}"
                                                @if ((string) $class->id == $selectedClass) selected @endif>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-lg-right">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#import-csv-modal">
                                    <i class="mdi mdi-file-table"></i> Tải lên file CSV
                                </button>
                            </div>
                        </div><!-- end col-->
                        <form action="{{ route('admin.students.export_csv') }}" method="POST" class="form-group ml-2">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $selectedCourse }}">
                            <input type="hidden" name="major_id" value="{{ $selectedMajor }}">
                            <input type="hidden" name="class_id" value="{{ $selectedClass }}">
                            <button class="btn btn-primary">
                                <i class="mdi mdi-file-excel"></i> Xuất file Excel
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>Mã SV</th>
                                    <th>Avatar</th>
                                    <th>Tên</th>
                                    <th>Giới tính</th>
                                    <th>Tuổi</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Lớp</th>
                                    <th>Tình trạng</th>
                                    <th>Chỉnh sửa</th>
                                    <th>Xoá</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($data as $each)
                                    <tr class="text-center">
                                        <td>{{ $each->student_code }}</td>
                                        <td>
                                            @if ($each->avatar)
                                                <img src="{{ asset('storage/' . $each->avatar) }}"
                                                    class="img-fluid avatar-lg">
                                            @endif
                                        </td>
                                        <td>{{ $each->name }}</td>
                                        <td>{{ $each->gender_name }}</td>
                                        <td>{{ $each->age }}</td>
                                        <td>
                                            @if ($each->email)
                                                <a href="mailto:{{ $each->email }}">
                                                    {{ $each->email }}
                                                </a>
                                            @else
                                                <i class="dripicons-wrong"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($each->phone_number)
                                                {{ $each->phone_number }}
                                            @else
                                                <i class="dripicons-wrong"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if (optional($each->class)->name != null)
                                                {{ optional($each->class)->name }}
                                            @else
                                                <i class="dripicons-wrong"></i>
                                            @endif
                                        </td>
                                        <td>{{ $each->status_name }}</td>
                                        <td>
                                            <a href="{{ route('admin.students.edit', ['student' => $each->id]) }}">
                                                <button type="button" class="btn btn-info"><i class="mdi mdi-pen"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" id="delete-btn" class="btn btn-danger"
                                                data-toggle="modal" data-target="#warning-confirm-delete-modal"
                                                data-href="{{ route('admin.students.destroy', ['student' => $each->id]) }}">
                                                <i class="dripicons-trash"></i>
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

    <!-- Warning Confirm Delete Modal -->
    <div id="warning-confirm-delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-warning h1 text-warning"></i>
                        <h4 class="mt-2">Bạn có chắc chắn muốn xoá sinh viên này ?</h4>
                        <button type="button" id="confirm-delete"
                            class="btn btn-outline-primary my-2 btn-rounded">Có</button>
                        <button type="button" class="btn btn-outline-danger my-2 btn-rounded"
                            data-dismiss="modal">Không</button>
                    </div>
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
                        <label for="studentsPerClass">Số sinh viên mỗi lớp</label>
                        <input id="studentsPerClass" class="form-control" type="number">
                    </div>

                    <div class="form-group">
                        <p>Chọn file CSV để tải lên</p>
                        <input type="file" name="csv" id="csv"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                    </div>

                    <div class="form-group">
                        <a href="{{ route('admin.students.export_sample_csv') }}"><em>Tải file CSV mẫu</em></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn-import-csv">
                        Tải lên
                    </button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
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
        $(document).ready(function() {
            //filter user
            $(".select-filter").change(function(e) {
                $("#form-filter").submit();
            });

            //confirm delete
            $(document).on('click', '#delete-btn', function(event) {
                event.preventDefault();
                let href = $(this).data('href');
                let currentDeleteBtn = $(this);

                $("#confirm-delete").click(function() {
                    $.ajax({
                        type: "POST",
                        url: href,
                        success: function(response) {
                            $("#warning-confirm-delete-modal").modal('hide');
                            currentDeleteBtn.parents("tr").prev().remove();
                            currentDeleteBtn.parents("tr").remove();
                            $.toast({
                                heading: 'Xoá thành công người dùng',
                                showHideTransition: 'slide',
                                icon: 'success',
                                stack: false,
                            });
                        }
                    });

                });
            });

            //csv import
            $("#btn-import-csv").click(function() {
                let formData = new FormData();
                formData.append("file", $("#csv")[0].files[0]);
                formData.append("studentsPerClass", $("#studentsPerClass").val());

                $(this).prop('disabled', true);
                $(this).html("<span role='btn-status'></span>Đang tải lên");
                $("span[role='btn-status']").attr("class", "spinner-border spinner-border-sm mr-1");

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.students.import_csv') }}',
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
                            text: response.responseJSON.message,
                            showHideTransition: 'fade',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@endpush
