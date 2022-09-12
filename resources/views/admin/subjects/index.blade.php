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
                            <form class="form-inline">
                                <div class="form-group mb-2">
                                    <div class="input-group form-group">
                                        <input type="text" class="form-control" placeholder="Tìm khoá..."
                                            aria-label="Recipient's username" name="q">
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
                                <a href="{{ route('admin.majors.index') }}" class="btn btn-secondary md-2 mr-2">
                                    Quản lý chuyên ngành
                                    <i class="dripicons-arrow-right"></i>
                                </a>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Số tín chỉ</th>
                                    <th>Ngành</th>
                                    <th>Chỉnh sửa</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
