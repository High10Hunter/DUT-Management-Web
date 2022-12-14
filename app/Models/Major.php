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

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'major_subject')
            ->withPivot('course_id', 'number_of_credits');
    }

    public static function getMajorsInCourse($courseId)
    {
        $majors = [];
        $classes = _Class::query()->clone()->with(['major:id,name'])
            ->where('course_id', (int)$courseId)->get();
        foreach ($classes as $class) {
            if (in_array($class->major, $majors))
                continue;
            $majors[] = $class->major;
        }
        //change array to collection
        $majors = collect($majors);

        return $majors;
    }
}
