<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Exports\StudentsSampleExport;
use App\Models\Student;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Imports\StudentsImport;
use App\Models\_Class;
use App\Models\Course;
use App\Models\Major;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use ResponseTrait;
    private string $title = "Quản lý sinh viên";
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = Student::query();
        $this->table = (new Student())->getTable();
        View::share('title', $this->title);
    }

    public function index(Request $request)
    {
        $selectedCourse = $request->get('course_id');
        $selectedMajor = $request->get('major_id');
        $selectedClass = $request->get('class_id');
        $search = $request->get('q');

        $courses = Course::get([
            'id',
            'name'
        ]);

        $majors = [];
        if (!is_null($selectedCourse)) {
            $majors = Major::getMajorsInCourse($selectedCourse);

            $query = $this->model
                ->whereRelation('class', 'course_id', $selectedCourse)
                ->orderBy('name');

            if (!is_null($selectedMajor)) {
                $query = $this->model->clone()
                    ->whereRelation('class', 'major_id', $selectedMajor)
                    ->orderBy('name');
                $classes = _Class::query()
                    ->where('course_id', $selectedCourse)
                    ->where('major_id', $selectedMajor)
                    ->get([
                        'id',
                        'name'
                    ]);
            } else {
                $query = $this->model->clone()
                    ->with(['class:id,name'])
                    ->orderBy('name');
            }
        } else {
            $majors = Major::get([
                'id',
                'name'
            ]);

            $classes = _Class::get([
                'id',
                'name'
            ]);
        }

        if (!is_null($selectedMajor)) {
            $query = $this->model
                ->whereRelation('class', 'major_id', $selectedMajor)
                ->orderBy('name');

            $classes = _Class::query()
                ->where('major_id', $selectedMajor)
                ->get([
                    'id',
                    'name'
                ]);
        } else {
            $query = $this->model->clone()
                ->with(['class:id,name'])
                ->latest();

            $classes = _Class::query()
                ->where('course_id', $selectedCourse)
                ->get([
                    'id',
                    'name'
                ]);
        }

        if (!is_null($selectedClass)) {
            $query->where('class_id', $selectedClass);
        }

        if (!is_null($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('student_code', $search);
        }

        $data = $query->paginate(10)
            ->appends($request->all());


        return view("admin.$this->table.index", [
            'data' => $data,
            'search' => $search,
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'majors' => $majors,
            'selectedMajor' => $selectedMajor,
            'classes' => $classes,
            'selectedClass' => $selectedClass,
        ]);
    }

    public function importCSV(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $studentsPerClass = $request->input('studentsPerClass');

            Excel::import(new StudentsImport($studentsPerClass), $request->file('file'));
            DB::commit();
            return $this->successResponse([], 'File đã được tải lên');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Không thể tải file lên');
        }
    }

    public function exportSampleCSV()
    {
        return Excel::download(new StudentsSampleExport, 'sampleStudentsImport.csv');
    }

    public function exportCSV(Request $request)
    {
        $courseId = $request->input('course_id');
        $majorId = $request->input('major_id');
        $classId = $request->input('class_id');

        $fileName = "DSSV";
        if (isset($courseId)) {
            $courseName = Course::query()->where('id', $courseId)->value('name');
            $fileName = $fileName . '-' . $courseName;
        }
        if (isset($majorId)) {
            $majorName = Major::query()->where('id', $majorId)->value('name');
            $fileName = $fileName . '-' . $majorName;
        }
        if (isset($classId)) {
            $className = _Class::query()->where('id', $classId)->value('name');
            $fileName = $fileName . '-' . $className;
        }
        $fileExtension = ".xlsx";
        $fileName = $fileName . $fileExtension;

        return (new StudentsExport($courseId, $majorId, $classId))->download($fileName);
    }

    public function edit(Student $student)
    {
        return view("admin.$this->table.edit", [
            'student' => $student,
        ]);
    }

    public function update(UpdateStudentRequest $request, $studentId)
    {
        $updateArr = [];
        $updateArr = $request->validated();

        if ($request->file('new_avatar')) {
            //remove old image
            if (!is_null($request->old_avatar))
                //Storage:: get into 'storage/app' path
                Storage::delete('public/' . $request->old_avatar);

            //upload new avatar
            $path = $request->file('new_avatar')->store(
                'avatars/users',
                'public'
            );

            //move new avatar to userID folder
            $userId = $this->model
                ->where('id', $studentId)
                ->value('user_id');

            $newPath = moveAvatarToUserIDFolderWhenUpdate(
                $userId,
                $path,
                'public/avatars/users/',
                'avatars/users/'
            );

            $updateArr['avatar'] = $newPath;
        } else
            $updateArr['avatar'] = $request->old_avatar;

        $this->model
            ->where('id', $studentId)
            ->update($updateArr);

        $userId = $this->model->where('id', $studentId)->value('user_id');

        User::query()
            ->where('id', $userId)
            ->update([
                'name' => $request->name,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);

        session()->put('success', 'Cập nhật thông tin thành công');
        return redirect()->route("admin.$this->table.index");
    }

    public function destroy($studentId)
    {
        if (isAdmin())
            $this->model->where('id', $studentId)->delete();
        return redirect()->route("admin.$this->table.index");
    }
}
