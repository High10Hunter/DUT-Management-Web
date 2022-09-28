<?php

namespace App\Models;

use App\Enums\StudentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        if ($this->status === StudentStatusEnum::DROP_OUT)
            return 'Đã nghỉ';
        else if ($this->status === StudentStatusEnum::ACTIVE)
            return 'Đi học';
        else if ($this->status === StudentStatusEnum::RESERVED)
            return 'Bảo lưu';
    }

    public function getAgeAttribute()
    {
        return date_diff(date_create($this->birthday), date_create())->y;
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(_Class::class, 'class_id');
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_student');
    }

    public function attendance(): HasOne
    {
        return $this->hasOne(PeriodAttendanceDetail::class);
    }

    public function attendances(): BelongsToMany
    {
        return $this->belongsToMany(Period::class, 'period_attendance_details');
    }
}
