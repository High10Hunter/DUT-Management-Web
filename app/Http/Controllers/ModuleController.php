<?php

namespace App\Http\Controllers;

use App\Imports\ModulesImport;
use App\Models\Lecturer;
use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ModuleController extends Controller
{
    use ResponseTrait;
    public object $model;
    public string $table;

    public function __construct()
    {
        $this->model = Module::query();
        $this->table = (new Module())->getTable();
    }

    public function index(Request $request)
    {
        $search = $request->get('q');

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
        ]);
    }

    public function importCSV(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {

            Excel::import(new ModulesImport(), $request->file('file'));
            DB::commit();
            return $this->successResponse();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse($th->getMessage());
        }
    }

    public function edit(Module $module)
    {
        $lecturers = Lecturer::get([
            'id',
            'name'
        ]);

        $schedule = $module->schedule;
        $schedule = explode(',', $schedule);

        $beginDate = $module->begin_date;
        $endDate = $module->end_date;

        return view("admin.$this->table.edit", [
            'module' => $module,
            'lecturers' => $lecturers,
            'schedule' => $schedule,
            'beginDate' => $beginDate,
            'endDate' => $endDate,
        ]);
    }

    public function update(Request $request, $moduleId)
    {
        $lecturer_id = $request->input('lecturer_id');
        $startSlot = $request->input('start_slot');
        $endSlot = $request->input('end_slot');
        $schedule = $request->input('schedule');
        $beginDate = $request->input('begin_date');
        $endDate = $request->input('end_date');


        $schedule = implode(',', $schedule);

        $this->model->where('id', $moduleId)
            ->update([
                'lecturer_id' => $lecturer_id,
                'schedule' => $schedule,
                'start_slot' => $startSlot,
                'end_slot' =>   $endSlot,
                'begin_date' => $beginDate,
                'end_date' =>   $endDate,
            ]);

        session()->put('success', 'Cập nhật học phần thành công');
        return redirect()->route("admin.$this->table.index");
    }
}
