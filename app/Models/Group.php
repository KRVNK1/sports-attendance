<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'coach_id'
    ];

    // Тренер группы
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // Спортсмены группы
    public function athletes()
    {
        return $this->belongsToMany(User::class, 'athlete_group', 'group_id', 'athlete_id'); // группа принадлежит многим спортсменам, this ссылается на модель Group
    }

    //  Тренировки группы
    public function trainings()
    {
        return $this->hasMany(Training::class); // группа имеет много тренировок
    }

    // Кол-во спортсменов в группе
    public function getAthletesCountAttribute()
    {
        return $this->athletes()->count();
    }
    
    // Получить количество тренировок группы
    public function getTrainingsCountAttribute()
    {
        return $this->trainings()->count();
    }
}
