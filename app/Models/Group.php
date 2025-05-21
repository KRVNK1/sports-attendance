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

    /**
     * Получить тренера группы
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // Спортсмены группы
    public function athletes()
    {
        return $this->belongsToMany(User::class, 'athlete_group', 'group_id', 'athlete_id');
    }


    //  Тренировки группы
     
    public function trainings()
    {
        return $this->hasMany(Training::class);
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
