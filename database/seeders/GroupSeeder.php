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
        $coaches = User::where('role', 'coach')->pluck('id')->toArray();

        $groups = [
            [
                'name' => 'Взрослые',
                'description' => '123',
                'coach_id' => $coaches[0],
            ],
            [
                'name' => 'Дети',
                'description' => '123',
                'coach_id' => $coaches[1],
            ],
            [
                'name' => 'Юниоры',
                'description' => '123',
                'coach_id' => $coaches[2],
            ]
        ];
        
        foreach ($groups as $groupData) {
            Group::create($groupData);
        }
    }
}
