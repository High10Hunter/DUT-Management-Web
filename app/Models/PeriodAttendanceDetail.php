<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodAttendanceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'student_id',
    ];

    public $timestamps = false;
}
