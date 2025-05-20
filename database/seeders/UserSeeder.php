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
        User::create([
            'name' => 'Администратор',
            'surname' => 'Системы',
            'email' => 'admin@bk.ru',
            'phone' => '79001234567',
            'birth' => '1990-01-01',
            'password' => '12345678',
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Никита',
            'surname' => 'Курашов',
            'email' => 'krvnk@bk.ru',
            'phone' => '89086402804',
            'birth' => '2005-12-14',
            'password' => '12345678',
            'role' => 'athlete'
        ]);

        User::create([
            'name' => 'Тренер',
            'surname' => 'Иванов',
            'email' => 'ivanov@bk.ru',
            'phone' => '89085402804',
            'birth' => '2005-12-20',
            'password' => '12345678',
            'role' => 'coach'
        ]);
    }
}
