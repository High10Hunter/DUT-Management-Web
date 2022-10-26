<?php

namespace App\Http\Controllers;

use App\Exports\ExamStudentsExport;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Models\Exam;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Lecturer;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ExamController extends Controller
{
    use ResponseTrait;
    private object $model;
    private string $table;
    private string $title = "Quản lý lịch thi";

    public function __construct()
    {
        $this->model = Exam::query();
        $this->table = (new Exam())->getTable();
        View::share('title', $this->title);
    }

    public function index(Request $request)
    {
        $currentYear = now()->format('Y');
        $search = $request->get('q');

        $query = $this->model
            ->where('date', '>=', $currentYear . '-01-01')
            ->with(
                [
                    'module' => function ($q) {
                        $q->with('subject:id,name');
                    },
                    'proctor:id,name',
                ]
            );

        if (!is_null($search)) {
            $query->whereHas('module', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate(10);

        return view("admin.$this->table.index", [
            'data' => $data,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $modules = Module::query()
            ->whereDoesntHave('exam')
            ->where('status', 1)
            ->with('subject:id,name')
            ->get();

        $lecturers = Lecturer::query()->get();

        return view("admin.$this->table.schedule-view", [
            'modules' => $modules,
            'lecturers' => $lecturers,
        ]);
    }

    public function getExams()
    {
        $exams = Exam::getExams();

        return response()->json($exams);
    }

    public function store(StoreExamRequest $request)
    {
        DB::beginTransaction();
        try {
            $moduleIds = $request->safe()->module_id;
            $date = $request->safe()->date;
            $type = $request->safe()->type;
            $startSlot = $request->safe()->start_slot;
            $proctorId = $request->safe()->proctor_id;

            Exam::storeExams(
                $moduleIds,
                $date,
                $type,
                $startSlot,
                $proctorId
            );

            $modules = Module::query()
                ->whereDoesntHave('exam')
                ->where('status', 1)
                ->get([
                    'id',
                    'name',
                ]);

            DB::commit();

            return response()->json([
                'data' => $modules,
                'status' => true,
                'message' => 'Tạo thành công lịch thi',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function getStudentsInExam(Request $request): JsonResponse
    {
        try {
            $moduleId = $request->get('module_id');
            $students = Exam::find($moduleId)
                ->students()
                ->with('class:id,name')
                ->get();

            return $this->successResponse($students, "Success");
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function exportCSV(Request $request)
    {
        $moduleId = $request->input('module_id');
        $moduleName = $request->input('module_name');
        $moduleDate = $request->input('module_date');

        $moduleDateArr = explode('-', $moduleDate);

        $moduleDate = $moduleDateArr[2] . '-' . $moduleDateArr[1] . '-' . $moduleDateArr[0];

        $fileName = "DSSV" .  ' - ' . $moduleName . ' ' . $moduleDate;
        $fileExtension = ".xlsx";

        $fileName .= $fileExtension;

        return (new ExamStudentsExport($moduleId))->download($fileName);
    }

    public function examProctoring()
    {
        return view('lecturers.exams.index');
    }

    public function getExamsForLecturer()
    {
        $lecturerId = auth()->user()->lecturer->id;
        $exams = Exam::getExamsForLecturer($lecturerId);

        return response()->json($exams);
    }

    public function displayExamsScheduleForStudent()
    {
        return view('students.exam-schedule');
    }

    public function getExamsForStudent()
    {
        $studentId = auth()->user()->student->id;
        $exams = Exam::getExamsForStudent($studentId);

        return response()->json($exams);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateExamRequest  $request
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        //
    }
}
