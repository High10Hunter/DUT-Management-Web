<?php

namespace App\Models;

use App\Enums\TimeSlotEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'exam_attendance_details');
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
        $data = self::query()
            ->with([
                'module' => function ($q) {
                    $q->with('subject:id,name');
                },
                'proctor:id,name',
            ])
            ->get();

        foreach ($data as $each) {
            $id = $each->id;
            $moduleName = $each->module->name;
            $subjectName = $each->module->subject->name;
            $startTime = TimeSlotEnum::getStartTimeBySlotId($each->start_slot);
            $date = $each->date;
            $type = $each->type_name;
            $proctorName = $each->proctor->name;

            $exams[$id][] = [
                'moduleName' => $moduleName,
                'subjectName' => $subjectName,
                'proctorName' => $proctorName,
                'type' => $type,
                'start' => $date . ' ' . $startTime,
                'startTime' => Carbon::parse($startTime)->format('H:i'),
            ];
        }

        $generalExams = [];
        $privateExams = [];
        foreach ($exams as $each) {
            if (count($each) > 1)
                $generalExams[] = $each;
            else
                $privateExams[] = $each;
        }

        $exams = [];
        $examTypes = [
            'general' => 'Thi chung',
            'private' => 'Thi riêng',
        ];
        //take out general exams
        foreach ($generalExams as $generalExam) {
            $generalTitle = $generalExam[0]['subjectName'];
            $generalTitle .= " - ";
            for ($i = 0; $i < count($generalExam) - 1; $i++) {
                $generalTitle  .= $generalExam[$i]['moduleName'];
                $generalTitle .= ", ";
            }
            $generalTitle .= $generalExam[count($generalExam) - 1]['moduleName'];

            $exams[] = [
                'title' => $generalTitle,
                'start' => $generalExam[0]['start'],
                'extendedProps' => [
                    'proctorName' => $generalExam[0]['proctorName'],
                    'type' => $generalExam[0]['type'] . ' - ' . $examTypes['general'],
                    'startTime' => $generalExam[0]['startTime'],
                ],
                'color' => 'red',
            ];
        }

        //take out private exams
        foreach ($privateExams as $each) {
            $exams[] = [
                'title' => $each[0]['subjectName'] . ' - ' . $each[0]['moduleName'],
                'start' => $each[0]['start'],
                'extendedProps' => [
                    'proctorName' => $each[0]['proctorName'],
                    'type' => $each[0]['type'] . ' - ' . $examTypes['private'],
                    'startTime' => $each[0]['startTime'],
                ],
            ];
        }

        return $exams;
    }

    public static function getExamsForLecturer($lecturerId)
    {
        $exams = [];
        $data = self::query()
            ->where('proctor_id', $lecturerId)
            ->with([
                'module' => function ($q) {
                    $q->with('subject:id,name');
                },
            ])
            ->get();

        foreach ($data as $each) {
            $id = $each->id;
            $moduleName = $each->module->name;
            $subjectName = $each->module->subject->name;
            $startTime = TimeSlotEnum::getStartTimeBySlotId($each->start_slot);
            $date = $each->date;
            $type = $each->type_name;

            $exams[$id][] = [
                'moduleName' => $moduleName,
                'subjectName' => $subjectName,
                'type' => $type,
                'start' => $date . ' ' . $startTime,
                'startTime' => Carbon::parse($startTime)->format('H:i'),
            ];
        }

        $generalExams = [];
        $privateExams = [];
        foreach ($exams as $each) {
            if (count($each) > 1)
                $generalExams[] = $each;
            else
                $privateExams[] = $each;
        }

        $exams = [];
        $examTypes = [
            'general' => 'Thi chung',
            'private' => 'Thi riêng',
        ];
        //take out general exams
        foreach ($generalExams as $generalExam) {
            $generalTitle = $generalExam[0]['subjectName'];
            $generalTitle .= " - ";
            for ($i = 0; $i < count($generalExam) - 1; $i++) {
                $generalTitle  .= $generalExam[$i]['moduleName'];
                $generalTitle .= ", ";
            }
            $generalTitle .= $generalExam[count($generalExam) - 1]['moduleName'];

            $exams[] = [
                'title' => $generalTitle,
                'start' => $generalExam[0]['start'],
                'extendedProps' => [
                    'type' => $generalExam[0]['type'] . ' - ' . $examTypes['general'],
                    'startTime' => $generalExam[0]['startTime'],
                ],
                'color' => 'red',
            ];
        }

        //take out private exams
        foreach ($privateExams as $each) {
            $exams[] = [
                'title' => $each[0]['subjectName'] . ' - ' . $each[0]['moduleName'],
                'start' => $each[0]['start'],
                'extendedProps' => [
                    'type' => $each[0]['type'] . ' - ' . $examTypes['private'],
                    'startTime' => $each[0]['startTime'],
                ],
            ];
        }

        return $exams;
    }

    public static function getExamsForStudent($studentId)
    {
        $exams = [];

        $modules = Module::query()
            ->whereRelation('students', 'student_id', $studentId)
            ->pluck('id');

        $data = self::query()
            ->whereIn('module_id', $modules)
            ->with([
                'module' => function ($q) {
                    $q->with('subject:id,name');
                },
                'proctor:id,name',
            ])
            ->get();

        foreach ($data as $each) {
            $id = $each->id;
            $moduleName = $each->module->name;
            $subjectName = $each->module->subject->name;
            $startTime = TimeSlotEnum::getStartTimeBySlotId($each->start_slot);
            $date = $each->date;
            $type = $each->type_name;
            $proctorName = $each->proctor->name;

            $exams[$id][] = [
                'moduleName' => $moduleName,
                'subjectName' => $subjectName,
                'proctorName' => $proctorName,
                'type' => $type,
                'start' => $date . ' ' . $startTime,
                'startTime' => Carbon::parse($startTime)->format('H:i'),
            ];
        }

        $generalExams = [];
        $privateExams = [];
        foreach ($exams as $each) {
            if (count($each) > 1)
                $generalExams[] = $each;
            else
                $privateExams[] = $each;
        }

        $exams = [];
        $examTypes = [
            'general' => 'Thi chung',
            'private' => 'Thi riêng',
        ];
        //take out general exams
        foreach ($generalExams as $generalExam) {
            $generalTitle = $generalExam[0]['subjectName'];
            $generalTitle .= " - ";
            for ($i = 0; $i < count($generalExam) - 1; $i++) {
                $generalTitle  .= $generalExam[$i]['moduleName'];
                $generalTitle .= ", ";
            }
            $generalTitle .= $generalExam[count($generalExam) - 1]['moduleName'];

            $exams[] = [
                'title' => $generalTitle,
                'start' => $generalExam[0]['start'],
                'extendedProps' => [
                    'proctorName' => $generalExam[0]['proctorName'],
                    'type' => $generalExam[0]['type'] . ' - ' . $examTypes['general'],
                    'startTime' => $generalExam[0]['startTime'],
                ],
                'color' => 'red',
            ];
        }

        //take out private exams
        foreach ($privateExams as $each) {
            $exams[] = [
                'title' => $each[0]['subjectName'] . ' - ' . $each[0]['moduleName'],
                'start' => $each[0]['start'],
                'extendedProps' => [
                    'proctorName' => $each[0]['proctorName'],
                    'type' => $each[0]['type'] . ' - ' . $examTypes['private'],
                    'startTime' => $each[0]['startTime'],
                ],
            ];
        }

        return $exams;
    }

    public static function storeExams(
        $moduleIds,
        $date,
        $type,
        $startSlot,
        $proctorId
    ) {
        if (count($moduleIds) > 1) {
            $subjectIds = Module::query()
                ->whereIn('id', $moduleIds)
                ->distinct()
                ->pluck('subject_id');

            //check if modules has same subject 
            if (count($subjectIds) > 1) {
                dd("Không tạo được lịch thi");
            }

            foreach ($moduleIds as $moduleId) {
                $checkExistModule = self::query()
                    ->where('module_id', $moduleId)
                    ->value('id');

                if (isset($checkExistModule)) {
                    dd("Không tạo được lịch thi");
                }
            }
        }

        $examId = self::create([
            'module_id' => $moduleIds[0],
            'date' =>  $date,
            'type' => $type,
            'start_slot' => $startSlot,
            'proctor_id' => $proctorId,
        ])->id;

        for ($i = 0; $i < count($moduleIds); $i++) {
            self::firstOrCreate([
                'id' => $examId,
                'module_id' => $moduleIds[$i],
                'date' =>  $date,
                'type' => $type,
                'start_slot' => $startSlot,
                'proctor_id' => $proctorId,
            ]);

            $module = Module::getModule($moduleIds[$i]);
            $periods = $module->periods()->get();
            $periodsId = $periods->pluck('id');
            $configs = Config::getAndCache();

            $query = Student::query()
                ->getStudentsCanTakeExams($moduleIds[$i], $periodsId);
            $students = $query->get();

            $examStudents = [];
            foreach ($students as $student) {
                if (
                    getTotalAbsentLessons($student->not_attended_count, $student->late_count, $configs['late_coefficient']) <=
                    count($periodsId) * $configs['exam_ban_coefficient']
                ) {
                    $examStudents[] = $student->id;
                }
            }

            foreach ($examStudents as $each) {
                ExamAttendanceDetail::insert([
                    'exam_id' => $examId,
                    'student_id' => $each,
                ]);
            }
        }
    }
}
