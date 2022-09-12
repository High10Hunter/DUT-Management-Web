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
                            <form class="form-inline">
                                <div class="form-group mb-2">
                                    <label for="inputPassword2" class="sr-only">Search</label>
                                    <input type="search" class="form-control" id="inputPassword2" placeholder="Search...">
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
                                <tr>
                                    <th>#</th>
                                    <th>Tên khoá</th>
                                    <th>Năm bắt đầu</th>
                                    <th>Năm kết thúc</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td>{{ $course->id }}</td>
                                        <td>{{ $course->name }}</td>
                                        <td>
                                            @if ($course->begin_academic_year)
                                                {{ $course->begin_academic_year->format('Y') }}
                                            @else
                                                <i class="dripicons-wrong"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($course->end_academic_year)
                                                {{ $course->end_academic_year->format('Y') }}
                                            @else
                                                <i class="dripicons-wrong"></i>
                                            @endif
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
