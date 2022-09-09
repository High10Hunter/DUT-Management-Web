@extends('admin_layout.master')
@push('css')
    <!--Datatable-->
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/buttons.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/select.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4>Quản lý người dùng</h4>
            </div>

            {{-- success notification when adding new staff --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <a href="" class="btn btn-primary">
                        Thêm mới
                    </a>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#import-csv-modal">
                        Tải lên file CSV
                    </button>
                    <form id="form-filter" method="GET" class="form-inline float-right">
                        <div class="col-lg-6">
                            <label for="role">Vai trò</label>
                            <select class="custom-select mb-3 select-filter" id="role" name="role">
                                <option value="" selected>Tất cả</option>
                                @foreach ($roles as $role => $value)
                                    <option value="{{ $value }}" @if ((string) $value == $selectedRole) selected @endif>
                                        {{ $role }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="status">Tình trạng</label>
                            <select class="custom-select mb-3 select-filter" name="status">
                                <option value="" selected>Tất cả</option>
                                @foreach ($status as $each => $value)
                                    <option value="{{ $value }}" @if ((string) $value == $selectedStatus) selected @endif>
                                        {{ $each }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <div class="card-body container-fluid">
                    <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Tên</th>
                                <th>Giới tính</th>
                                <th>Tuổi</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Tình trạng</th>
                                <th>Vai trò</th>
                                <th>Khoa</th>
                                <th>Lớp</th>
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
                                            <img src="{{ asset('storage/' . $each->avatar) }}" class="img-fluid avatar-lg">
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
                                    <td>{{ $each->status_name }}</td>
                                    <td>{{ $each->role_name }}</td>
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
                                    <td>
                                        <a href="">
                                            <button type="button" class="btn btn-info"><i class="mdi mdi-pen"></i>
                                            </button>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" id="delete-btn" class="btn btn-danger" data-toggle="modal"
                                            data-target="#warning-confirm-delete-modal" data-href="">
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
    <!--Datatable-->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.select.min.js') }}"></script>

    <!-- Datatable Init js -->
    <script src="{{ asset('js/demo.datatable-init.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select-filter").change(function(e) {
                $("#form-filter").submit();
            });

        });
        $("#state-saving-datatable").DataTable({
            retrieve: true,
            info: false,
            paging: false,
        });
    </script>
@endpush
