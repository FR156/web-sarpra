<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{   
    use WithoutModelEvents; 
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Alat Tulis',
            'Elektronik',
            'Peralatan Laboratorium',
            'Peralatan Olahraga',
            'Peralatan Kebersihan',
            'Peralatan Multimedia',
            'Peralatan Kelas',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
    }
}
