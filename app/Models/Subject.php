<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function majors()
    {
        return $this->belongsToMany(Major::class, 'major_subject')
            ->withPivot('course_id', 'number_of_credits');
    }
}
