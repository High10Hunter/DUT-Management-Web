<?php

namespace App\Models;

use App\Enums\PeriodAttendanceStatusEnum;
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
        'birthday',
        'gender',
        'email',
        'phone_number',
        'role',
        'status',
        'class_id',
        'user_id',
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
        return $this->belongsToMany(Period::class, 'period_attendance_details')
            ->withPivot('status');
    }

    public function scopeStudentAttendanceOverallStatus($query, $moduleId, $attendance)
    {
        return $query
            ->whereRelation('modules', 'module_id', $moduleId)
            ->with([
                'attendance' => function ($q) use ($attendance) {
                    $q->where('period_id', optional($attendance)->id);
                },
                'class:id,name',
            ])
            ->withCount([
                'attendances as not_attended_count' => function ($q) {
                    $q->where('status', PeriodAttendanceStatusEnum::NOT_ATTENDED);
                },
                'attendances as excused_count'  => function ($q) {
                    $q->where('status', PeriodAttendanceStatusEnum::EXCUSED);
                },
                'attendances as late_count'  => function ($q) {
                    $q->where('status', PeriodAttendanceStatusEnum::LATE);
                },
            ]);
    }

    public function scopeGetStudentsHistoryAttendance($query, $moduleId, $periodsId)
    {
        return $query
            ->whereRelation('modules', 'module_id', $moduleId)
            ->with([
                'attendances' => function ($q) use ($periodsId) {
                    $q->whereIn('period_id', $periodsId);
                },
                'class:id,name',
            ])
            ->withCount([
                'attendances as not_attended_count' => function ($q) {
                    $q->where('status', PeriodAttendanceStatusEnum::NOT_ATTENDED);
                },
                'attendances as late_count'  => function ($q) {
                    $q->where('status', PeriodAttendanceStatusEnum::LATE);
                },
            ])
            ->get()
            ->map(function ($each) {
                $each->class_name = $each->class->name;
                unset($each->class);
                return $each;
            });
    }
}
