<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'faculty_id',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public $timestamps = false;

    public function subjects($courseId = null)
    {
        if (is_null($courseId)) {
            return $this->belongsToMany(Subject::class, 'major_subject')
                ->using(MajorSubject::class)
                ->withPivot('course_id', 'number_of_credits');
        }

        return $this->belongsToMany(Subject::class, 'major_subject')
            ->using(MajorSubject::class)
            ->withPivot('course_id', 'number_of_credits')
            ->wherePivot('course_id', $courseId);
    }
}
