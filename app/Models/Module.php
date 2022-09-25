<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getSlotRangeAttribute()
    {
        return $this->start_slot . ' - ' . $this->end_slot;
    }

    public function getStudyTimeAttribute()
    {
        $beginDate = Carbon::parse($this->begin_date)->format('d/m');
        $endDate = Carbon::parse($this->end_date)->format('d/m');

        return $beginDate . ' - ' . $endDate;
    }

    public function getStatusNameAttribute()
    {
        return ($this->status === 1) ? 'Đang học' : 'Kết thúc';
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'module_student');
    }
}
