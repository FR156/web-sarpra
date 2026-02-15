<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        $mapping = [
            'Alat Tulis' => [
                'Spidol Snowman',
                'Penghapus Papan',
                'Pulpen Pilot',
            ],
            'Elektronik' => [
                'Laptop Asus',
                'Proyektor Epson',
                'Speaker Aktif',
                'Mouse Wireless',
            ],
            'Peralatan Laboratorium' => [
                'Mikroskop Biologi',
                'Tabung Reaksi',
                'Gelas Ukur',
            ],
            'Peralatan Olahraga' => [
                'Bola Basket',
                'Bola Voli',
                'Net Badminton',
            ],
            'Peralatan Kebersihan' => [
                'Sapu Lantai',
                'Pel Lantai',
                'Ember Plastik',
            ],
            'Peralatan Multimedia' => [
                'Kamera DSLR',
                'Tripod Kamera',
                'Microphone Wireless',
            ],
            'Peralatan Kelas' => [
                'Papan Tulis Lipat',
                'Meja Lipat',
                'Kursi Plastik',
            ],
        ];

        foreach ($mapping as $categoryName => $items) {

            $category = Category::where('name', $categoryName)->first();

            if (!$category) continue;

            foreach ($items as $itemName) {

                $words = explode(' ', $itemName);

                $prefixParts = collect($words)
                    ->take(2)
                    ->map(function ($word) {
                        return strtoupper(Str::substr($word, 0, 3));
                    });

                $prefix = $prefixParts->implode('-');

                Item::create([
                    'name' => $itemName,
                    'prefix' => $prefix,
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
