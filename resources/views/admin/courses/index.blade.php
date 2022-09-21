@extends('admin_layout.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý khoá</h4>
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
                            <a href="{{ route('admin.courses.index') }}">
                                <i class="mdi mdi-reload"> Tải lại</i>
                            </a>
                            <form class="form-inline">
                                <div class="form-group mb-2">
                                    <div class="input-group form-group">
                                        <input type="text" class="form-control" placeholder="Tìm khoá..."
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
                                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary md-2 mr-2">
                                    <i class="mdi mdi-plus-circle mr-2"></i> Thêm mới
                                </a>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Tên khoá</th>
                                    <th>Năm bắt đầu</th>
                                    <th>Năm kết thúc</th>
                                    <th>Chỉnh sửa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $each)
                                    <tr class="text-center">
                                        <td>{{ $each->id }}</td>
                                        <td>{{ $each->name }}</td>
                                        <td>
                                            @if ($each->begin_academic_year)
                                                {{ $each->begin_academic_year->format('Y') }}
                                            @else
                                                <i class="dripicons-wrong"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($each->end_academic_year)
                                                {{ $each->end_academic_year->format('Y') }}
                                            @else
                                                <i class="dripicons-wrong"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.courses.edit', ['course' => $each->id]) }}">
                                                <button type="button" class="btn btn-info"><i class="mdi mdi-pen"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
