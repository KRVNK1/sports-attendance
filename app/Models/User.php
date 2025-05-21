<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'password',
        'role',
        'birth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Проверка ролей
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCoach()
    {
        return $this->role === 'coach';
    }

    public function isAthlete()
    {
        return $this->role === 'athlete';
    }

    // Группы, в которых состоит спортсмен
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'athlete_group', 'athlete_id', 'group_id');
    }

    // Тренировки, которые ведет тренер
    public function coachedTrainings()
    {
        return $this->hasMany(Training::class, 'coach_id');
    }

    //  Группы, которую ведет тренер
    public function coachedGroups()
    {
        return $this->hasMany(Group::class, 'coach_id');
    }

    // Посещения спортсмена
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'athlete_id');
    }
}
