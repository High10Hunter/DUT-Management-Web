<?php

namespace App\Http\Controllers;

use App\Exports\MajorsSampleExport;
use App\Models\Major;
use App\Http\Requests\StoreMajorRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Imports\MajorsImport;
use App\Models\Faculty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;


class MajorController extends Controller
{
    use ResponseTrait;
    private string $title = "Quản lý chuyên ngành";
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Major::query();
        $this->table = (new Major())->getTable();
        View::share('title', $this->title);
    }

    public function index(Request $request)
    {
        $selectedFaculty = $request->get('faculty_id');
        $faculties = Faculty::get([
            'id',
            'name',
        ]);

        $search = $request->get('q');
        $query = $this->model->clone()
            ->with(['faculty:id,name']);

        if (!is_null($selectedFaculty)) {
            $query->where('faculty_id', $request->get('faculty_id'));
        }

        $query = $query->where('name', 'like', '%' . $search . '%');

        $data = $query->orderBy('id', 'desc')->paginate(10)
            ->appends($request->all());

        return view("admin.$this->table.index", [
            'data' => $data,
            'faculties' => $faculties,
            'selectedFaculty' => $selectedFaculty,
            'search' => $search,
        ]);
    }

    public function importCSV(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            Excel::import(new MajorsImport, $request->file('file'));
            DB::commit();
            return $this->successResponse([], 'File đã được tải lên, tải lại trang để thấy sự thay đổi');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Không thể tải file lên');
        }
    }

    public function exportSampleCSV()
    {
        return Excel::download(new MajorsSampleExport, 'sampleMajorsImport.xlsx');
    }

    public function edit(Major $major)
    {
        $faculties = Faculty::get([
            'id',
            'name',
        ]);

        return view("admin.$this->table.edit", [
            'major' => $major,
            'faculties' => $faculties,
        ]);
    }

    public function update(Request $request, $majorId)
    {
        $updateArr = [];
        $updateArr = $request->validate([
            'name' => 'required',
            'faculty_id' => 'required',
        ]);

        $this->model->where('id', $majorId)
            ->update($updateArr);

        session()->put('success', 'Cập nhật người dùng thành công');
        return redirect()->route("admin.$this->table.index");
    }
}
