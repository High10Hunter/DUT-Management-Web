<?php

namespace App\Http\Controllers;

use App\Exports\SubjectsSampleExport;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Imports\SubjectsImport;
use App\Models\_Class;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Major;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class SubjectController extends Controller
{
    use ResponseTrait;
    private string $title = "Quản lý môn";
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Subject::query();
        $this->table = (new Subject())->getTable();
        View::share('title', $this->title);
    }

    public function index(Request $request)
    {
        $selectedCourse = $request->get('course_id');
        $selectedMajor = $request->get('major_id');
        $search = $request->get('q');

        $courses = Course::get([
            'id',
            'name'
        ]);

        $coursesArr = [];
        foreach ($courses as $course) {
            $coursesArr[$course->id] = $course->name;
        }

        $majors = [];
        if (!is_null($selectedCourse)) {
            $classes = _Class::query()->clone()->with(['major:id,name'])
                ->where('course_id', (int)$selectedCourse)->get();
            foreach ($classes as $class) {
                if (in_array($class->major, $majors))
                    continue;
                $majors[] = $class->major;
            }
            //change array to collection
            $majors = collect($majors);

            if (!is_null($selectedMajor))
                $selectedMajorName = Major::find($selectedMajor)->name;
            else
                $selectedMajorName = null;

            if (!is_null($selectedMajor)) {
                $query = Major::find((int)$selectedMajor)->subjects($selectedCourse);
            } else {
                $query = $this->model->clone()
                    ->with(['majors:id,name']);
            }
        } else {
            $majors = Major::get([
                'id',
                'name'
            ]);
        }

        if (!is_null($selectedMajor))
            $selectedMajorName = Major::find($selectedMajor)->name;
        else
            $selectedMajorName = null;

        if (!is_null($selectedMajor)) {
            $query = Major::find((int)$selectedMajor)->subjects($selectedCourse);
        } else {
            $query = $this->model->clone()
                ->with(['majors:id,name']);
        }

        if (!is_null($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->orderBy('id', 'desc')->paginate(10)
            ->appends($request->all());

        return view("admin.$this->table.index", [
            'data' => $data,
            'majors' => $majors,
            'coursesArr' => $coursesArr,
            'selectedMajor' => $selectedMajor,
            'selectedCourse' => $selectedCourse,
            'selectedMajorName' => $selectedMajorName,
            'search' => $search,
        ]);
    }

    public function importCSV(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            Excel::import(new SubjectsImport, $request->file('file'));
            DB::commit();
            return $this->successResponse([], 'Tải file lên thành công, tải lại trang để thấy sự thay đổi');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Không thể tải file lên');
        }
    }

    public function exportSampleCSV()
    {
        return Excel::download(new SubjectsSampleExport, 'sampleSubjectsImport.xlsx');
    }
}
