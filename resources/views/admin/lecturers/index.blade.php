@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý giảng viên</h4>
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
                            <a href="{{ route('admin.lecturers.index') }}">
                                <i class="mdi mdi-reload"> Tải lại</i>
                            </a>
                            <form id="form-filter" method="GET" class="form-inline">
                                <div class="form-group">
                                    <div class="input-group form-group mx-sm-1">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm giảng viên..."
                                            aria-label="Recipient's username" name="q" value="{{ $search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mx-sm-1 mb-3">
                                    <label for="major-select" class="mr-2">Khoa</label>
                                    <select id="major_select" class="select2 select-filter" data-toggle="select2"
                                        name="faculty_id">
                                        <option value="" selected>Tất cả</option>
                                        @foreach ($faculties as $faculty)
                                            <option value="{{ $faculty->id }}"
                                                @if ((string) $faculty->id == $selectedFaculty) selected @endif>
                                                {{ $faculty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-lg-right">
                                <a href="{{ route('admin.lecturers.create') }}" id="btn-create-module" type="button"
                                    class="btn btn-primary">
                                    <i class="mdi mdi-account-plus"></i> Thêm giảng viên
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>Mã GV</th>
                                    <th>Avatar</th>
                                    <th>Tên</th>
                                    <th>Giới tính</th>
                                    <th>Tuổi</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Khoa</th>
                                    <th>Chỉnh sửa</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($data as $each)
                                    <tr class="text-center">
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
                                            @if (optional($each->faculty)->name != null)
                                                {{ optional($each->faculty)->name }}
                                            @else
                                                <i class="dripicons-wrong"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.lecturers.edit', ['lecturer' => $each->id]) }}">
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
        });
    </script>
@endpush
