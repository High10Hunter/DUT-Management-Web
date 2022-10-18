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
        'name',
        'avatar',
        'username',
        'password',
        'gender',
        'birthday',
        'email',
        'phone_number',
        'role',
        'status',
        'faculty_id',
        'class_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getGenderNameAttribute(): string
    {
        return ($this->gender === 1) ? 'Nam' : 'Nữ';
    }

    public function getStatusNameAttribute(): string
    {
        return ($this->status === 1) ? 'Làm việc' : 'Đã nghỉ';
    }

    public function getAgeAttribute(): int
    {
        return date_diff(date_create($this->birthday), date_create())->y;
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

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function class()
    {
        return $this->belongsTo(_Class::class);
    }

    public function lecturer(): HasOne
    {
        return $this->hasOne(Lecturer::class);
    }
}
