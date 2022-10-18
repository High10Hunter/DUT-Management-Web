<?php

namespace App\Models;

use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EAO_staff extends Model
{
    use HasFactory;
    public $table = "eao_staffs";

    protected $fillable = [
        'name',
        'birthday',
        'gender',
        'email',
        'phone_number',
        'status',
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
}
