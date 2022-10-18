<?php

namespace App\Http\Controllers;

use App\Exports\ModulesSampleExport;
use App\Exports\ModuleStudentSampleExport;
use App\Http\Requests\Module\StoreModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;
use App\Imports\ModulesImport;
use App\Imports\ModuleStudentImport;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\Module;
use App\Models\ModuleStudent;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class ModuleController extends Controller
{
    use ResponseTrait;
    private string $title = "Phân công giảng dạy";
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Module::query();
        $this->table = (new Module())->getTable();
        View::share('title', $this->title);
    }

    public function index(Request $request)
    {
        $search = $request->get('q');
        $subjects = Subject::query()->get([
            'id',
            'name'
        ]);
        $faculties = Faculty::query()->get([
            'id',
            'name'
        ]);

        $query = $this->model->clone()
            ->with([
                'subject:id,name',
                'lecturer:id,name',
            ])
            ->orderBy('id', 'desc');

        if (!is_null($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->paginate(10)
            ->appends($request->all());


        return view("admin.$this->table.index", [
            'data' => $data,
            'search' => $search,
            'subjects' => $subjects,
            'faculties' => $faculties,
        ]);
    }

    public function getLecturers(Request $request): JsonResponse
    {
        $facultyId = $request->input('faculty_id');
        $lecturers = Lecturer::query()
            ->where('faculty_id', $facultyId)->get([
                'id',
                'name',
            ]);

        return $this->successResponse($lecturers, "Success");
    }

    public function generateModuleName($subjectId, $index)
    {
        return $subjectId . '.' . now()->format('Y') . '.' . 'Nh' . ($index + 1);
    }

    public function store(StoreModuleRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $index = $this->model->where('subject_id', $data['subject_id'])->count();
            $data['name'] = $this->generateModuleName($data['subject_id'], $index);
            $this->model->insert($data);
            return $this->successResponse([], "Đã tạo thành công học phần, tải lại trang để thấy sự thay đổi");
        } catch (\Throwable $th) {
            return $this->errorResponse("Đã có lỗi xảy ra, vui lòng kiểm tra thông tin nhập vào");
        }
    }

    public function getStudentsInModule(Request $request): JsonResponse
    {
        try {
            $moduleId = $request->get('module_id');
            $students = Module::find($moduleId)
                ->students()
                ->with('class:id,name')
                ->get();

            return $this->successResponse($students, "Success");
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function importStudentListCSV(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $moduleId = $request->get('module_id');

            Excel::import(new ModuleStudentImport($moduleId), $request->file('file'));
            DB::commit();
            return $this->successResponse([], 'File đã được tải lên');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Không thể tải file lên');
        }
    }

    public function exportSampleStudentListCSV()
    {
        return Excel::download(new ModuleStudentSampleExport, 'sampleModuleStudentImport.xlsx');
    }

    public function importCSV(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {

            Excel::import(new ModulesImport(), $request->file('file'));
            DB::commit();
            return $this->successResponse([], 'File đã được tải lên, tải lại trang để thấy sự thay đổi');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Không thể tải file lên');
        }
    }

    public function exportSampleCSV()
    {
        return Excel::download(new ModulesSampleExport, 'sampleModulesImport.xlsx');
    }

    public function edit(Module $module)
    {
        $lecturers = Lecturer::get([
            'id',
            'name'
        ]);

        $schedule = $module->schedule;
        $beginDate = $module->begin_date;
        $lessons = $module->lessons;

        return view("admin.$this->table.edit", [
            'module' => $module,
            'lecturers' => $lecturers,
            'schedule' => $schedule,
            'beginDate' => $beginDate,
            'lessons' => $lessons,
        ]);
    }

    public function update(UpdateModuleRequest $request, $moduleId)
    {
        $lecturer_id = $request->input('lecturer_id');
        $startSlot = $request->input('start_slot');
        $endSlot = $request->input('end_slot');
        $schedule = $request->input('schedule');
        $beginDate = $request->input('begin_date');
        $lessons = $request->input('lessons');

        $this->model->where('id', $moduleId)
            ->update([
                'lecturer_id' => $lecturer_id,
                'schedule' => json_encode($schedule),
                'start_slot' => $startSlot,
                'end_slot' =>   $endSlot,
                'begin_date' => $beginDate,
                'lessons' => (int)$lessons,
            ]);

        session()->put('success', 'Cập nhật học phần thành công');
        return redirect()->route("admin.$this->table.index");
    }
}
