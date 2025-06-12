<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;


class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // pluck - 
        $coaches = User::where('role', 'coach')->pluck('id')->toArray();

        $groups = [
            [
                'name' => 'Взрослые',
                'description' => 'Группа для взрослых спортсменов. Тренировки направлены на поддержание физической формы и совершенствование техники.',
                'coach_id' => $coaches[0],
            ],
            [
                'name' => 'Дети',
                'description' => 'Детская группа для начинающих спортсменов. Основной упор на изучение базовых элементов и развитие координации',
                'coach_id' => $coaches[1],
            ],
            [
                'name' => 'Юниоры',
                'description' => 'Группа для подростков с базовой подготовкой. Интенсивные тренировки с элементами соревновательной подготовки.',
                'coach_id' => $coaches[2],
            ]
        ];
        
        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}
