<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
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


        $roles = UserRoleEnum::getRolesForFilter();
        $status = UserStatusEnum::getStatusForFilter();

        $query = $this->model
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

        $data = $query->paginate()
            ->appends($request->all());

        return view("admin.$this->table.index", [
            'data' => $data,
            'roles' => $roles,
            'status' => $status,
            'selectedRole' => $selectedRole,
            'selectedStatus' => $selectedStatus,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
