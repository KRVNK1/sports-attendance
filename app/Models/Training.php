<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'start_time',
        'end_time',
        'location',
        'notes',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Группа тренировки
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // Тренер
    public function coach()
    {
        return $this->group->coach();
    }

    // Посещаемость тренировки
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // День недели тренировки
    public function getDayOfWeekAttribute()
    {
        $days = [
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота',
            'Воскресенье',
        ];

        return $days[$this->start_time->dayOfWeek];
    }

    // Получить время начала тренировки в формате часы:минуты
    public function getStartTimeFormattedAttribute()
    {
        return $this->start_time->format('H:i');
    }

    // Получить время окончания тренировки в формате часы:минуты
    public function getEndTimeFormattedAttribute()
    {
        return $this->end_time->format('H:i');
    }
}
