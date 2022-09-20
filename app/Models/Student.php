<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'student_code',
        'name',
        'avatar',
        'username',
        'password',
        'birthday',
        'gender',
        'email',
        'phone_number',
        'role',
        'status',
        'class_id',
        'user_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function getGenderNameAttribute(): string
    {
        return ($this->gender === 1) ? 'Nam' : 'Nữ';
    }

    public function getStatusNameAttribute(): string
    {
        return ($this->status === 1) ? 'Đi học' : 'Bảo lưu';
    }

    public function getAgeAttribute()
    {
        return date_diff(date_create($this->birthday), date_create())->y;
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(_Class::class, 'class_id');
    }
}
