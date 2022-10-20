<?php

namespace App\Http\Controllers;

use App\Enums\TimeSlotEnum;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Models\Exam;
use App\Http\Requests\UpdateExamRequest;
use App\Imports\ExamsImport;
use App\Models\Config;
use App\Models\ExamAttendanceDetail;
use App\Models\Lecturer;
use App\Models\Module;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

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
                    'module:id,name',
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
            ->get([
                'id',
                'name',
            ]);
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
        try {
            $moduleIds = $request->safe()->module_id;
            $date = $request->safe()->date;
            $type = $request->safe()->type;
            $startSlot = $request->safe()->start_slot;
            $proctorId = $request->safe()->proctor_id;

            foreach ($moduleIds as $moduleId) {
                $examId = Exam::create([
                    'module_id' => $moduleId,
                    'date' => $date,
                    'type' => $type,
                    'start_slot' => $startSlot,
                    'proctor_id' => $proctorId,
                ])->id;

                $module = Module::getModule($moduleId);
                $periods = $module->periods()->get();
                $periodsId = $periods->pluck('id');
                $configs = Config::getAndCache();

                $query = Student::query()
                    ->getStudentsCanTakeExams($moduleId, $periodsId);
                $students = $query->get();

                $examStudents = [];
                foreach ($students as $student) {
                    if (
                        getTotalAbsentLessons($student->not_attended_count, $student->late_count, $configs['late_coefficient']) <=
                        count($periodsId) * $configs['exam_ban_coefficient']
                    ) {
                        $examStudents[] = $student->id;
                    }
                }

                foreach ($examStudents as $each) {
                    ExamAttendanceDetail::insert([
                        'exam_id' => $examId,
                        'student_id' => $each,
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Booking created successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
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
    public function update(UpdateExamRequest $request, Exam $exam)
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
