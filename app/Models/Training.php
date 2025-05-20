<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'group_id',
        'coach_id',
        'start_time',
        'end_time',
        'location',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    

     // Группа, для которой проводится тренировка
     public function group()
     {
         return $this->belongsTo(Group::class);
     }
 
     // Тренер, проводящий тренировку
     public function coach()
     {
         return $this->belongsTo(User::class, 'coach_id');
     }
 
     // Посещения тренировки
     public function attendances()
     {
         return $this->hasMany(Attendance::class);
     }
 
     // Спортсмены, которые должны быть на тренировке (из группы)
     public function expectedAthletes()
     {
         return $this->group->athletes();
     }
}
