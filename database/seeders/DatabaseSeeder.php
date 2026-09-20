<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
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
        // 1. Seed Kategori Buku
        $this->call(CategorySeeder::class);

        // 2. Buat Akun Dummy Admin (ERP Admin)
        $admin = User::firstOrCreate(
            ['email' => 'admin@booksoed.ac.id'],
            [
                'name'        => 'Admin BookSoed',
                'password'    => Hash::make('password'),
                'role'        => 'admin',
                'nim_nip'     => '198501012010121001',
                'is_verified' => true,
            ]
        );

        // 3. Buat Akun Dummy Seller (Mahasiswa Penjual Buku)
        $seller = User::firstOrCreate(
            ['email' => 'seller@unsoed.ac.id'],
            [
                'name'        => 'Budi Penjual',
                'password'    => Hash::make('password'),
                'role'        => 'student',
                'nim_nip'     => 'H1A021001',
                'is_verified' => true,
            ]
        );

        // 4. Buat Akun Dummy Buyer (Mahasiswa Pembeli Buku)
        $buyer = User::firstOrCreate(
            ['email' => 'buyer@unsoed.ac.id'],
            [
                'name'        => 'Siti Pembeli',
                'password'    => Hash::make('password'),
                'role'        => 'student',
                'nim_nip'     => 'H1A021002',
                'is_verified' => true,
            ]
        );

        // 5. Seed Beberapa Contoh Buku Milik Seller
        $kuliahCat = Category::where('name', 'Buku Kuliah')->first();
        $novelCat  = Category::where('name', 'Novel')->first();

        if ($kuliahCat && $seller) {
            Book::firstOrCreate(
                ['title' => 'Algoritma dan Pemrograman Python', 'user_id' => $seller->id],
                [
                    'category_id' => $kuliahCat->id,
                    'author'      => 'Dr. Indah Unsoed',
                    'isbn'        => '978-602-1234-56-7',
                    'condition'   => 'LIKE_NEW',
                    'price'       => 45000,
                    'status'      => 'AVAILABLE',
                    'description' => 'Buku perkuliahan Informatika semester 2, kondisi masih sangat mulus tanpa coretan.',
                ]
            );

            Book::firstOrCreate(
                ['title' => 'Sistem Informasi Manajemen ERP', 'user_id' => $seller->id],
                [
                    'category_id' => $kuliahCat->id,
                    'author'      => 'Prof. Jenderal Soedirman',
                    'isbn'        => '978-602-9876-54-3',
                    'condition'   => 'GOOD',
                    'price'       => 60000,
                    'status'      => 'AVAILABLE',
                    'description' => 'Buku referensi lengkap modul ERP, SCM, dan CRM untuk mahasiswa Unsoed.',
                ]
            );
        }

        if ($novelCat && $seller) {
            Book::firstOrCreate(
                ['title' => 'Laskar Pelangi', 'user_id' => $seller->id],
                [
                    'category_id' => $novelCat->id,
                    'author'      => 'Andrea Hirata',
                    'isbn'        => '978-979-3062-79-2',
                    'condition'   => 'GOOD',
                    'price'       => 35000,
                    'status'      => 'AVAILABLE',
                    'description' => 'Novel inspiratif bekas koleksi pribadi, kertas masih bersih.',
                ]
            );
        }
    }
}
