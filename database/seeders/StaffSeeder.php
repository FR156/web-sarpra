<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'staff',
            'email' => 'staff@mail.com',
            'password' => bcrypt('staff1234'),
            'role' => 'staff',
        ]);
    }
}
