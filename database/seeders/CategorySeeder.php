<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed kategori buku dasar untuk BookSoed marketplace.
     */
    public function run(): void
    {
        $categories = [
            'Buku Kuliah',
            'Novel',
            'Komik',
            'Referensi',
            'Lainnya',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
