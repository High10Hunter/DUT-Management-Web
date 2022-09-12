<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MajorSubject extends Model
{
    use HasFactory;
    public $table = 'major_subject';

    protected $fillable = [
        'major_id',
        'subject_id',
    ];

    public $timestamps = false;
}
