@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý môn</h4>
            </div>
        </div>
    </div>

    {{-- success notification when adding or updating new course --}}
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
                            <a href="{{ route('admin.subjects.index') }}">
                                <i class="mdi mdi-reload"> Tải lại</i>
                            </a>
                            <form id="form-filter" method="GET" class="form-inline">
                                <div class="form-group">
                                    <div class="input-group form-group">
                                        <input type="text" class="form-control" placeholder="Tìm môn..."
                                            aria-label="Recipient's username" name="q" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
                                        </div>
                                    </div>
                                    <div class="form-group mx-sm-3 mb-3">
                                        <label for="course-select" class="mr-2">Khoá</label>
                                        <select class="select2 select-filter" data-toggle="select2" name="course_id">
                                            <option value="" selected>Tất cả</option>
                                            @foreach ($coursesArr as $courseId => $courseName)
                                                <option value="{{ $courseId }}"
                                                    @if ((string) $courseId == $selectedCourse) selected @endif>
                                                    {{ $courseName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mx-sm-3 mb-3">
                                        <label for="major-select" class="mr-2">Ngành</label>
                                        <select class="select2 select-filter" data-toggle="select2" name="major_id">
                                            <option value="" selected>Tất cả</option>
                                            @foreach ($majors as $major)
                                                <option value="{{ $major->id }}"
                                                    @if ((string) $major->id == $selectedMajor) selected @endif>
                                                    {{ $major->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-lg-right">
                                <button type="button" class="btn btn-success md-2 mr-2" data-toggle="modal"
                                    data-target="#import-csv-modal">
                                    <i class="mdi mdi-file-table"></i> Tải lên file CSV
                                </button>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Ngành</th>
                                    <th>Số tín chỉ</th>
                                    <th>Khoá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($selectedCourse)
                                    @foreach ($data as $each)
                                        @foreach ($each->majors as $major)
                                            @if ($selectedCourse == $major->pivot->course_id)
                                                <tr class="text-center">
                                                    <td>{{ $each->id }}</td>
                                                    <td>{{ $each->name }}</td>
                                                    <td>{{ $major->name }}</td>
                                                    <td>{{ $major->pivot->number_of_credits }}</td>
                                                    <td>{{ $coursesArr[$major->pivot->course_id] }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @elseif ($selectedMajor)
                                    @foreach ($data as $each)
                                        <tr class="text-center">
                                            <td>{{ $each->id }}</td>
                                            <td>{{ $each->name }}</td>
                                            <td>{{ $selectedMajorName }}</td>
                                            <td>{{ $each->pivot->number_of_credits }}</td>
                                            <td>{{ $coursesArr[$each->pivot->course_id] }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($data as $each)
                                        @foreach ($each->majors as $major)
                                            <tr class="text-center">
                                                <td>{{ $each->id }}</td>
                                                <td>{{ $each->name }}</td>
                                                <td>{{ $major->name }}</td>
                                                <td>{{ $major->pivot->number_of_credits }}</td>
                                                <td>{{ $coursesArr[$major->pivot->course_id] }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <nav>
                            @if (is_null($selectedCourse))
                                <ul class="pagination pagination-rounded mb-0">
                                    {{ $data->links() }}
                                </ul>
                            @endif
                        </nav>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

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
                        <a href="{{ route('admin.subjects.export_sample_csv') }}"><em>Tải file mẫu</em></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn-import-csv">Tải lên</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Đóng</button>
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

            $(document).ready(function() {
                $(".select-filter").change(function(e) {
                    $("#form-filter").submit();
                });

                //csv import
                $("#btn-import-csv").click(function() {
                    let formData = new FormData();
                    formData.append("file", $("#csv")[0].files[0]);

                    $(this).prop('disabled', true);
                    $(this).html("<span role='btn-status'></span>Đang tải lên");
                    $("span[role='btn-status']").attr("class", "spinner-border spinner-border-sm mr-1");


                    $.ajax({
                        type: 'POST',
                        url: '{{ route('admin.subjects.import_csv') }}',
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
            });
        </script>
    @endpush
@endsection
