<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'date',
        'lecturer_id',
    ];

    public $timestamps = false;

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'period_attendance_details')
            ->withPivot('status');
    }

    public function getPeriodDateAttribute()
    {
        return Carbon::parse($this->date)->format('d/m');
    }
}
