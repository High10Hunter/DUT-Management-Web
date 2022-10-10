<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'lecturer_id',
        'schedule',
        'start_slot',
        'end_slot',
        'begin_date',
        'end_date',
        'status',
    ];

    public $timestamps = false;

    protected $casts = [
        'schedule' => 'array',
    ];

    public function getSlotRangeAttribute()
    {
        return $this->start_slot . ' - ' . $this->end_slot;
    }

    public function getStudyTimeAttribute()
    {
        $beginDate = Carbon::parse($this->begin_date)->format('d/m');

        return $beginDate;
    }

    public function getStatusNameAttribute()
    {
        return ($this->status === 1) ? 'Đang học' : 'Chưa mở';
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'module_student');
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }
}
