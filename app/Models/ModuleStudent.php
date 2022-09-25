<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleStudent extends Model
{
    use HasFactory;
    public $table = 'module_student';

    protected $fillable = [
        'module_id',
        'student_id',
    ];

    public $timestamps = false;
}
