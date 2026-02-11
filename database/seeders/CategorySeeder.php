<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Komputer'],
            ['name' => 'Monitor'],
            ['name' => 'Alat Kebersihan'],
            ['name' => 'Alat Tulis Menulis'],
            ['name' => 'Peralatan Lainnya'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
