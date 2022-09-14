<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number_of_credits',
    ];

    public $timestamps = false;

    public function majors($courseId = null)
    {
        if (is_null($courseId)) {
            return $this->belongsToMany(Major::class, 'major_subject')
                ->using(MajorSubject::class)
                ->withPivot('course_id', 'number_of_credits');
        }

        return $this->belongsToMany(Major::class, 'major_subject')
            ->using(MajorSubject::class)
            ->withPivot('course_id', 'number_of_credits')
            ->wherePivot('course_id', $courseId);
    }
}
