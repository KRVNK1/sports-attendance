<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;

class AthleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем спортсменов
        $athletes = [
            // Спортсмены для группы "Взрослые"
            [
                'name' => 'Алексей',
                'surname' => 'Петров',
                'email' => 'petrov@bk.ru',
                'phone' => '89001234501',
                'birth' => '1995-03-15',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Мария',
                'surname' => 'Сидорова',
                'email' => 'sidorova@bk.ru',
                'phone' => '89001234502',
                'birth' => '1992-07-22',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Дмитрий',
                'surname' => 'Козлов',
                'email' => 'kozlov@bk.ru',
                'phone' => '89001234503',
                'birth' => '1988-11-08',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Елена',
                'surname' => 'Морозова',
                'email' => 'morozova@bk.ru',
                'phone' => '89001234504',
                'birth' => '1990-05-12',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Сергей',
                'surname' => 'Волков',
                'email' => 'volkov@bk.ru',
                'phone' => '89001234505',
                'birth' => '1987-09-30',
                'password' => '12345678',
                'role' => 'athlete'
            ],

            // Спортсмены для группы "Дети"
            [
                'name' => 'Анна',
                'surname' => 'Лебедева',
                'email' => 'lebedeva@bk.ru',
                'phone' => '89001234506',
                'birth' => '2010-04-18',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Максим',
                'surname' => 'Орлов',
                'email' => 'orlov@bk.ru',
                'phone' => '89001234507',
                'birth' => '2011-08-25',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'София',
                'surname' => 'Зайцева',
                'email' => 'zaitseva@bk.ru',
                'phone' => '89001234508',
                'birth' => '2009-12-03',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Артем',
                'surname' => 'Соколов',
                'email' => 'sokolov@bk.ru',
                'phone' => '89001234509',
                'birth' => '2010-06-14',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Виктория',
                'surname' => 'Павлова',
                'email' => 'pavlova@bk.ru',
                'phone' => '89001234510',
                'birth' => '2011-02-28',
                'password' => '12345678',
                'role' => 'athlete'
            ],

            // Спортсмены для группы "Юниоры"
            [
                'name' => 'Иван',
                'surname' => 'Новиков',
                'email' => 'novikov@bk.ru',
                'phone' => '89001234511',
                'birth' => '2005-01-20',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Полина',
                'surname' => 'Федорова',
                'email' => 'fedorova@bk.ru',
                'phone' => '89001234512',
                'birth' => '2006-10-15',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Андрей',
                'surname' => 'Михайлов',
                'email' => 'mikhailov@bk.ru',
                'phone' => '89001234513',
                'birth' => '2004-07-09',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Екатерина',
                'surname' => 'Романова',
                'email' => 'romanova@bk.ru',
                'phone' => '89001234514',
                'birth' => '2005-11-27',
                'password' => '12345678',
                'role' => 'athlete'
            ],
            [
                'name' => 'Владислав',
                'surname' => 'Кузнецов',
                'email' => 'kuznetsov@bk.ru',
                'phone' => '89001234515',
                'birth' => '2006-03-05',
                'password' => '12345678',
                'role' => 'athlete'
            ]
        ];

        // Создаем пользователей-спортсменов
        foreach ($athletes as $athlete) {
            User::create($athlete);
        }

        // Получаем группы
        $groups = Group::all();

        // Получаем всех созданных спортсменов
        $allAthletes = User::where('role', 'athlete')->get();

        // Распределяем спортсменов по группам (по 5 человек)
        $athleteIndex = 0;
        foreach ($groups as $group) {
            // Берем 5 спортсменов для каждой группы
            for ($i = 0; $i < 5; $i++) {
                if (isset($allAthletes[$athleteIndex])) {
                    $group->athletes()->attach($allAthletes[$athleteIndex]->id);
                    $athleteIndex++;
                }
            }
        }
    }
}
