@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý người dùng</h4>
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
                            <form id="form-filter" method="GET" class="form-inline">
                                <div class="form-group mb-2">
                                    <div class="input-group form-group">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm tên người dùng..."
                                            aria-label="Recipient's username" name="q" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="status-select" class="mr-2">Vai trò</label>
                                    <select class="custom-select select-filter" id="role" name="role">
                                        <option value="" selected>Tất cả</option>
                                        @foreach ($roles as $role => $value)
                                            <option value="{{ $value }}"
                                                @if ((string) $value == $selectedRole) selected @endif>
                                                {{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mx-sm-1 mb-2">
                                    <label for="status-select" class="mr-2">Tình trạng</label>
                                    <select class="custom-select select-filter" name="status">
                                        <option value="" selected>Tất cả</option>
                                        @foreach ($status as $each => $value)
                                            <option value="{{ $value }}"
                                                @if ((string) $value == $selectedStatus) selected @endif>
                                                {{ $each }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-lg-right">
                                <a href="{{ route('admin.users.create_eao_staff') }}" class="btn btn-primary md-2 mr-2">
                                    <i class="mdi mdi-plus-circle mr-2"></i> Thêm mới
                                </a>
                                {{-- <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#import-csv-modal">
                                    Tải lên file CSV
                                </button> --}}
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Avatar</th>
                                    <th>Tên</th>
                                    <th>Giới tính</th>
                                    <th>Tuổi</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Khoa</th>
                                    <th>Lớp</th>
                                    <th>Vai trò</th>
                                    <th>Tình trạng</th>
                                    <th>Chỉnh sửa</th>
                                    <th>Xoá</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($data as $each)
                                    <tr>
                                        <td>{{ $each->id }}</td>
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
                                            @if (optional($each->faculty)->name)
                                                {{ optional($each->faculty)->name }}
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
                                        <td>{{ $each->role_name }}</td>
                                        <td>{{ $each->status_name }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', ['user' => $each->id]) }}">
                                                <button type="button" class="btn btn-info"><i class="mdi mdi-pen"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" id="delete-btn" class="btn btn-danger"
                                                data-toggle="modal" data-target="#warning-confirm-delete-modal"
                                                data-href="{{ route('admin.users.destroy', ['user' => $each->id]) }}">
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
                        <h4 class="mt-2">Bạn có chắc chắn muốn xoá người dùng này ?</h4>
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
                        <p>Chọn file CSV để tải lên</p>
                        <input type="file" name="csv" id="csv"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn-import-csv">Tải lên</button>
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

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.users.import_csv') }}',
                    cache: false,
                    async: false,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function(response) {
                        $.toast({
                            heading: 'Thành công',
                            text: 'File đã được tải lên',
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'success'
                        });
                        $("#import-csv-modal").modal('hide');
                    },
                    error: function(response) {
                        $.toast({
                            heading: 'Thất bại',
                            text: 'Không thể tải file lên',
                            showHideTransition: 'fade',
                            icon: 'error'
                        })
                    }
                });
            });
        });
    </script>
@endpush
