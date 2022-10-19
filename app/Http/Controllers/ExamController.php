<?php

namespace App\Http\Controllers;

use App\Enums\TimeSlotEnum;
use App\Models\Exam;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Imports\ExamsImport;
use App\Models\Module;
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
                    'examiner:id,name',
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
        return view("admin.$this->table.schedule-view");
    }

    public function getExams()
    {
        $exams = Exam::getExams();

        return response()->json($exams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreExamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamRequest $request)
    {
        //
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
