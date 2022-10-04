<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\Lecturer\StoreLecturerRequest;
use App\Models\Lecturer;
use App\Models\Faculty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Lecturer::query();
        $this->table = (new Lecturer())->getTable();
    }

    public function index(Request $request)
    {
        $selectedFaculty = $request->get('faculty_id');
        $search = $request->get('q');

        $faculties = Faculty::query()->get();

        $query = $this->model
            ->with('faculty:id,name')
            ->latest();

        if (!is_null($selectedFaculty)) {
            $query->where('faculty_id', $selectedFaculty);
        }

        if (!is_null($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->paginate(10)
            ->appends($request->all());

        return view("admin.$this->table.index", [
            'selectedFaculty' => $selectedFaculty,
            'faculties' => $faculties,
            'search' => $search,
            'data' => $data,
        ]);
    }

    public function create()
    {
        $faculties = Faculty::query()->get();

        return view("admin.$this->table.create", [
            'faculties' => $faculties,
        ]);
    }

    public function store(StoreLecturerRequest $request)
    {
        $data = $request->validated();
        $birthday = $data['birthday'];
        $birthday = Carbon::createFromFormat('Y-m-d', $birthday)->format('d-m-Y');
        $password = explode('-', $birthday);
        $password = implode('', $password);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['email'],
            'password' => Hash::make($password),
            'gender' => $data['gender'],
            'birthday' => $data['birthday'],
            'email' => $data['email'],
            'role' => UserRoleEnum::LECTURER,
        ]);

        $data['user_id'] = $user->id;
        $this->model->insert($data);

        session()->put('success', 'Thêm mới giảng viên thành công');
        return redirect()->route("admin.$this->table.index");
    }

    public function edit(Lecturer $lecturer)
    {
        $faculties = Faculty::query()->get();

        return view("admin.$this->table.edit", [
            'lecturer' => $lecturer,
            'faculties' => $faculties,
        ]);
    }

    public function update(StoreLecturerRequest $request, $lecturerId)
    {
        $updateArr = $request->validated();
        $this->model->where('id', $lecturerId)
            ->update($updateArr);

        session()->put('success', 'Cập nhật giảng viên thành công');
        return redirect()->route("admin.$this->table.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lecturer $lecturer)
    {
        //
    }
}
