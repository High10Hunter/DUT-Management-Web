@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý chuyên ngành</h4>
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
                            <a href="{{ route('admin.majors.index') }}">
                                <i class="mdi mdi-reload"> Tải lại</i>
                            </a>
                            <form id="form-filter" method="GET" class="form-inline">
                                <div class="form-group mb-2">
                                    <div class="input-group form-group">
                                        <input type="text" class="form-control" placeholder="Tìm khoá..."
                                            aria-label="Recipient's username" name="q" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
                                        </div>
                                    </div>
                                    <div class="form-group mx-sm-3 mb-3">
                                        <label for="faculty-select" class="mr-2">Khoa</label>
                                        <select class="select2 select-filter" data-toggle="select2" name="faculty_id">
                                            <option value="" selected>Tất cả</option>
                                            @foreach ($faculties as $faculty)
                                                <option value="{{ $faculty->id }}"
                                                    @if ((string) $faculty->id == $selectedFaculty) selected @endif>
                                                    {{ $faculty->name }}
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
                                    <th>Tên ngành</th>
                                    <th>Khoa</th>
                                    <th>Chỉnh sửa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $each)
                                    <tr class="text-center">
                                        <td>{{ $each->id }}</td>
                                        <td>{{ $each->name }}</td>
                                        <td>{{ $each->faculty->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.majors.edit', ['major' => $each->id]) }}">
                                                <button type="button" class="btn btn-info"><i class="mdi mdi-pen"></i>
                                                </button>
                                            </a>
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
                        <a href="{{ route('admin.majors.export_sample_csv') }}"><em>Tải file CSV mẫu</em></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn-import-csv">Tải lên</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
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
                        url: '{{ route('admin.majors.import_csv') }}',
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
                            })
                        }
                    });
                });

            });
        </script>
    @endpush
@endsection
