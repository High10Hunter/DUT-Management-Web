<?php

namespace App\Models;

use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
        'birthday',
        'gender',
        'email',
        'phone_number',
        'role',
        'status',
        'faculty_id',
        'user_id',
    ];

    public function getGenderNameAttribute(): string
    {
        return ($this->gender === 1) ? 'Nam' : 'Nữ';
    }

    public function getStatusNameAttribute(): string
    {
        if ($this->status === UserStatusEnum::ACTIVE)
            return 'Hoạt động';
        else if ($this->status === UserStatusEnum::OFF)
            return 'Nghỉ';
    }

    public function getAgeAttribute()
    {
        return date_diff(date_create($this->birthday), date_create())->y;
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
}
