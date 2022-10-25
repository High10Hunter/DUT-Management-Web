<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Requests\User\StoreEAOStaffRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Imports\UsersImport;
use App\Models\_Class;
use App\Models\Faculty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use ResponseTrait;
    private string $title = "Quản lý người dùng";
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();
        View::share('title', $this->title);
    }

    public function index(Request $request)
    {
        $selectedRole = $request->get('role');
        $selectedStatus = $request->get('status');
        $search = $request->get('q');

        $roles = UserRoleEnum::getRolesForFilter();
        $status = UserStatusEnum::getStatusForFilter();

        $query = $this->model
            ->with(
                [
                    'lecturer',
                    'student',
                    'eao_staff',
                ]
            )
            ->latest();

        if (!is_null($selectedRole)) {
            $query->where('role', (int)$selectedRole);
        }
        if (!is_null($selectedStatus)) {
            $query->where('status', (int)$selectedStatus);
        }
        if (!is_null($search)) {
            $query
                ->whereHas('eao_staff', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('lecturer', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }
        $data = $query->paginate(10)
            ->appends($request->all());

        return view("admin.$this->table.index", [
            'data' => $data,
            'roles' => $roles,
            'status' => $status,
            'selectedRole' => $selectedRole,
            'selectedStatus' => $selectedStatus,
            'search' => $search,
        ]);
    }

    public function resetAccount($userId)
    {
        $updateArr = [];
        $userRole = $this->model->where('id', $userId)->value('role');

        $query = $this->model
            ->where('role', $userRole)
            ->with(
                [
                    'lecturer',
                    'student',
                    'eao_staff',
                ]
            )
            ->first();

        if ($userRole === UserRoleEnum::ADMIN || $userRole === UserRoleEnum::EAO_STAFF) {
            $email = $query->eao_staff->email;
            $birthday = createPasswordByBirthday($query->eao_staff->birthday);
        } else if ($userRole === UserRoleEnum::LECTURER) {
            $email = $query->lecturer->email;
            $birthday = createPasswordByBirthday($query->lecturer->birthday);
        } else if ($userRole === UserRoleEnum::STUDENT) {
            $email = $query->student->email;
            $birthday = createPasswordByBirthday($query->student->birthday);
        }

        $updateArr['username'] = $email;
        $updateArr['password'] = Hash::make($birthday);

        $this->model
            ->where('id', $userId)
            ->update(
                $updateArr
            );

        session()->put('success', 'Cập nhật người dùng thành công');
        return redirect()->route("admin.$this->table.index");
    }

    public function destroy($userId)
    {
        if (isAdmin())
            $this->model->where('id', $userId)->delete();
        return redirect()->route("admin.$this->table.index");
    }
}
