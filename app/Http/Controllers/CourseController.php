<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public object $model;
    public string $table;

    public function __construct()
    {
        $this->model = Course::query();
        $this->table = (new Course())->getTable();
    }

    public function index(Request $request)
    {
        $search = $request->get('q');

        $query = $this->model->where('name', 'like', '%' . $search . '%');

        $data = $query->paginate(10)
            ->appends($request->all());

        return view(
            "admin.$this->table.index",
            [
                'data' => $data,
                'search' => $search,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.$this->table.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseRequest $request)
    {
        $this->model->create($request->validated());

        session()->put('success', 'Thêm thành công khoá mời');
        return redirect()->route("admin.$this->table.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return view(
            "admin.$this->table.edit",
            [
                'course' => $course,
            ]
        );
    }

    public function update(StoreCourseRequest $request, $courseId)
    {
        $this->model->where('id', $courseId)->update($request->validated());

        session()->put('success', 'Cập nhật người dùng thành công');
        return redirect()->route("admin.$this->table.index");
    }
}
