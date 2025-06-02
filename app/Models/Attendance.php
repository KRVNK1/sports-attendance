<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'training_id',
        'athlete_id',
        'present',
    ];

    protected $casts = [
        'present' => 'boolean',
    ];

    // Сама тренировка
    public function training()
    {
        return $this->belongsTo(Training::class); // посещение принадлежит одной тренировке
    }

    // Спортсмен
    public function athlete()
    {
        return $this->belongsTo(User::class, 'athlete_id');
    }
}
