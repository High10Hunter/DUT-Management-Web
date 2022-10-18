<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\EAO_staff;
use App\Http\Requests\User\StoreEAOStaffRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class EAOStaffController extends Controller
{
    private string $title = "Thêm mới giáo vụ";
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = EAO_staff::query();
        $this->table = (new EAO_staff())->getTable();
        View::share('title', $this->title);
    }

    public function create()
    {
        return view("admin.$this->table.create");
    }

    public function store(StoreEAOStaffRequest $request)
    {
        $arr = [];
        $email = $request->safe()->email;
        $birthday = createPasswordByBirthday($request->safe()->birthday);

        $arr['role'] = UserRoleEnum::EAO_STAFF;
        $arr['username'] = $email;
        $arr['password'] = Hash::make($birthday);

        $newUser = User::create($arr);
        $arr = $request->validated();
        $arr['user_id'] = $newUser->id;

        $this->model->insert($arr);

        session()->put('success', 'Thêm người dùng thành công');
        return redirect()->route("admin.users.index");
    }
}
