<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Администратор',
                'surname' => 'Системы',
                'email' => 'admin@bk.ru',
                'phone' => '79001234567',
                'birth' => '2000-01-01',
                'password' => '12345678',
                'role' => 'admin'
            ],
            [
                'name' => 'Никита',
                'surname' => 'Курашов',
                'email' => 'krvnk@bk.ru',
                'phone' => '89086402804',
                'birth' => '2005-12-14',
                'password' => '12345678',
                'role' => 'athlete'
            ],

            [
                'name' => 'Тренер',
                'surname' => 'Иванов',
                'email' => 'ivanov@bk.ru',
                'phone' => '89085402804',
                'birth' => '2005-12-20',
                'password' => '12345678',
                'role' => 'coach'
            ],
            [
                'name' => 'Тренер',
                'surname' => 'Беляева',
                'email' => 'belyaeva@bk.ru',
                'phone' => '89025143027',
                'birth' => '2005-12-21',
                'password' => '12345678',
                'role' => 'coach'
            ],
            [
                'name' => 'Тренер',
                'surname' => 'Смирнов',
                'email' => 'smirniy@bk.ru',
                'phone' => '89025143026',
                'birth' => '2005-12-22',
                'password' => '12345678',
                'role' => 'coach'
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
