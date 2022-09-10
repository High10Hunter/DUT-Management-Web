<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Http\Requests\User\StoreUserRequest;
use App\Imports\UsersImport;
use App\Models\_Class;
use App\Models\Faculty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();
    }

    public function index(Request $request)
    {
        $selectedRole = $request->get('role');
        $selectedStatus = $request->get('status');
        $search = $request->get('q');

        $roles = UserRoleEnum::getRolesForFilter();
        $status = UserStatusEnum::getStatusForFilter();

        $query = $this->model->clone()
            ->with(
                [
                    'faculty:id,name',
                    'class:id,name',
                ]
            )
            ->latest();

        if (!is_null($selectedRole)) {
            $query->where('role', $request->get('role'));
        }
        if (!is_null($selectedStatus)) {
            $query->where('status', $request->get('status'));
        }

        $query->where('name', 'like', '%' . $search . '%');

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

    public function create()
    {
        $roles = UserRoleEnum::getRolesForFilter();
        $faculties = Faculty::get([
            'id',
            'name',
        ]);
        $classes = _Class::get([
            'id',
            'name',
        ]);
        return view(
            "admin.$this->table.create",
            [
                'roles' => $roles,
                'faculties' => $faculties,
                'classes' => $classes,
            ]
        );
    }

    public function store(StoreUserRequest $request)
    {
        $arr = [];
        $arr = $request->validated();

        //upload avatar
        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store(
                'avatars/users',
                'public'
            );
            $arr['avatar'] = $path;
        }
        $arr['username'] = UserRoleEnum::getRoleForAuthentication((int)$arr['role']);
        $arr['password'] = UserRoleEnum::getRoleForAuthentication((int)$arr['role']);

        $newUser = $this->model->create($arr);

        //move avatar to a folder with userID
        $newPath = null;
        if (!is_null($newUser->avatar)) {
            $newPath = moveAvatarToUserIDFolderWhenCreate(
                $newUser->id,
                'public/avatars/users/',
                $newUser->avatar,
                'avatars/users/'
            );
        }

        $afterInsertUpdate = [];
        $afterInsertUpdate['username'] = $newUser->username . $newUser->id;
        $afterInsertUpdate['password'] = Hash::make($newUser->password . $newUser->id);

        if ($newPath)
            $afterInsertUpdate['avatar'] = $newPath;

        $newUser::query()
            ->where('id', $newUser->id)
            ->update($afterInsertUpdate);

        session()->put('success', 'Thêm người dùng thành công');
        return redirect()->route("admin.$this->table.index");
    }

    public function edit(User $user)
    {
        $roles = UserRoleEnum::getRolesForFilter();
        $faculties = Faculty::get([
            'id',
            'name',
        ]);
        $classes = _Class::get([
            'id',
            'name',
        ]);

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'faculties' => $faculties,
            'classes' => $classes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUserRequest $request, $userId)
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
            $newPath = moveAvatarToUserIDFolderWhenUpdate(
                $userId,
                $path,
                'public/avatars/users/',
                'avatars/users/'
            );

            $updateArr['avatar'] = $newPath;
        } else
            $updateArr['avatar'] = $request->old_avatar;

        // reset username and password
        if ($request->resetAccount == "on") {
            $updateArr['username'] = UserRoleEnum::getRoleForAuthentication((int)$updateArr['role']) . $userId;
            $updateArr['password'] = Hash::make(UserRoleEnum::getRoleForAuthentication((int)$updateArr['role']) . $userId);
        }

        $this->model
            ->where('id', $userId)
            ->update(
                $updateArr
            );

        session()->put('success', 'Cập nhật người dùng thành công');
        return redirect()->route("admin.$this->table.index");
    }

    public function importCSV(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            Excel::import(new UsersImport, $request->file('file'));
            DB::commit();
            return $this->successResponse();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse($th->getMessage());
        }
    }

    public function destroy($userId)
    {
        $this->model->where('id', $userId)->delete();

        return redirect()->route("admin.$this->table.index");
    }
}
