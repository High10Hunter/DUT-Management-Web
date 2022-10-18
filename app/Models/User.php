<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getStatusNameAttribute(): string
    {
        return ($this->status === 1) ? 'Hoạt động' : 'Đã nghỉ';
    }

    public function getRoleNameAttribute(): string
    {
        $roleName = getRoleByValue($this->role);
        if ($roleName === 'admin')
            $roleName = 'Quản trị viên';
        else if ($roleName === 'eao_staff')
            $roleName = 'Giáo vụ';
        else if ($roleName === 'lecturer')
            $roleName = 'Giảng viên';
        else
            $roleName = 'Sinh viên';

        return $roleName;
    }

    public function lecturer(): HasOne
    {
        return $this->hasOne(Lecturer::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function eao_staff(): HasOne
    {
        return $this->hasOne(EAO_staff::class);
    }
}
