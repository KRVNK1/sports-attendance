<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    // Спортсмены в группе
    public function athletes()
    {
        return $this->belongsToMany(User::class, 'athlete_group', 'group_id', 'athlete_id')
            ->where('role', 'athlete');
    }

    // Тренировки группы
    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
