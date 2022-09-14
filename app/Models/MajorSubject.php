<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MajorSubject extends Pivot
{
    use HasFactory;
    public $table = 'major_subject';

    protected $fillable = [
        'major_id',
        'subject_id',
        'course_id',
        'number_of_credits',
    ];

    public $timestamps = false;
}
