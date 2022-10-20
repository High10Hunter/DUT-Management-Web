<?php

namespace App\Models;

use App\Enums\TimeSlotEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'module_id',
        'date',
        'type',
        'start_slot',
        'proctor_id',
    ];

    public $timestamps = false;

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function proctor(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'proctor_id');
    }

    public function getExamDateAttribute()
    {
        $date = Carbon::parse($this->date)->format('d/m');

        return $date;
    }

    public function getTypeNameAttribute()
    {
        return ($this->type === 0) ? 'Lý thuyết' : 'Thực hành';
    }

    public static function getExams()
    {
        $exams = [];
        $data = Exam::query()
            ->with([
                'module' => function ($q) {
                    $q->with('subject:id,name');
                },
                'proctor:id,name',
            ])
            ->get();

        foreach ($data as $each) {
            $moduleName = $each->module->name . ' - ' . $each->module->subject->name;
            $startTime = TimeSlotEnum::getStartTimeBySlotId($each->start_slot);
            $date = $each->date;
            $type = $each->type_name;
            $proctorName = $each->proctor->name;

            $exams[] = [
                'title' => $moduleName,
                'start' => $date . ' ' . $startTime,
                'extendedProps' => [
                    'proctorName' => $proctorName,
                    'type' => $type,
                    'startTime' => Carbon::parse($startTime)->format('H:i'),
                ]
            ];
        }

        return $exams;
    }
}
