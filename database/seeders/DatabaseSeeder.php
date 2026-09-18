<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed kategori buku
        $this->call(CategorySeeder::class);

        // Buat admin default (hanya jika belum ada)
        User::firstOrCreate(
            ['email' => 'admin@booksoed.ac.id'],
            [
                'name'     => 'Admin BookSoed',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );
    }
}
