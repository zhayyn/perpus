<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.local'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Buat Kategori Buku
        $categories = [
            ['name' => 'Fiksi', 'color' => '#6366f1', 'icon' => 'heroicon-o-book-open'],
            ['name' => 'Non-Fiksi', 'color' => '#f59e0b', 'icon' => 'heroicon-o-academic-cap'],
            ['name' => 'Sains & Teknologi', 'color' => '#10b981', 'icon' => 'heroicon-o-beaker'],
            ['name' => 'Sejarah', 'color' => '#8b5cf6', 'icon' => 'heroicon-o-clock'],
            ['name' => 'Ekonomi & Bisnis', 'color' => '#ef4444', 'icon' => 'heroicon-o-currency-dollar'],
            ['name' => 'Agama & Filsafat', 'color' => '#06b6d4', 'icon' => 'heroicon-o-heart'],
            ['name' => 'Anak & Remaja', 'color' => '#f97316', 'icon' => 'heroicon-o-face-smile'],
            ['name' => 'Biografi', 'color' => '#84cc16', 'icon' => 'heroicon-o-user'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                array_merge($cat, ['slug' => Str::slug($cat['name'])])
            );
        }

        // 3. Buat Contoh Buku
        $books = [
            ['title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'category' => 'Fiksi', 'copies' => 5, 'isbn' => '978-979-3601-45-6'],
            ['title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'category' => 'Fiksi', 'copies' => 3, 'isbn' => '978-979-707-296-0'],
            ['title' => 'Atomic Habits', 'author' => 'James Clear', 'category' => 'Non-Fiksi', 'copies' => 4, 'isbn' => '978-0-7352-1129-2'],
            ['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'category' => 'Sejarah', 'copies' => 3, 'isbn' => '978-0-06-231610-4'],
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'category' => 'Sains & Teknologi', 'copies' => 2, 'isbn' => '978-0-13-235088-4'],
            ['title' => 'Rich Dad Poor Dad', 'author' => 'Robert T. Kiyosaki', 'category' => 'Ekonomi & Bisnis', 'copies' => 4, 'isbn' => '978-1-61268-116-2'],
            ['title' => 'Harry Potter dan Batu Bertuah', 'author' => 'J.K. Rowling', 'category' => 'Anak & Remaja', 'copies' => 6, 'isbn' => '978-602-220-153-4'],
            ['title' => 'Steve Jobs', 'author' => 'Walter Isaacson', 'category' => 'Biografi', 'copies' => 2, 'isbn' => '978-1-4516-4853-9'],
        ];

        $copyCount = 1;
        foreach ($books as $bookData) {
            $category = Category::where('name', $bookData['category'])->first();
            $book = Book::firstOrCreate(
                ['isbn' => $bookData['isbn']],
                [
                    'category_id'    => $category->id,
                    'title'          => $bookData['title'],
                    'author'         => $bookData['author'],
                    'publisher'      => 'Penerbit Contoh',
                    'published_year' => rand(2010, 2023),
                    'description'    => "Deskripsi buku {$bookData['title']} karya {$bookData['author']}.",
                    'language'       => 'id',
                    'price'          => rand(50, 150) * 1000,
                    'is_active'      => true,
                ]
            );

            // Buat eksemplar buku
            for ($i = 1; $i <= $bookData['copies']; $i++) {
                BookCopy::firstOrCreate(
                    ['copy_code' => sprintf('BK-%04d-%02d', $book->id, $i)],
                    [
                        'book_id'    => $book->id,
                        'condition'  => 'baik',
                        'status'     => 'tersedia',
                        'acquired_at' => now()->subMonths(rand(1, 24)),
                    ]
                );
            }
        }

        // 4. Buat Contoh Anggota
        $memberTypes = ['siswa', 'mahasiswa', 'umum', 'guru'];
        for ($i = 1; $i <= 20; $i++) {
            Member::firstOrCreate(
                ['member_code' => sprintf('MBR-%04d', $i)],
                [
                    'name'             => "Anggota Contoh {$i}",
                    'email'            => "anggota{$i}@example.com",
                    'phone'            => '08' . rand(100000000, 999999999),
                    'type'             => $memberTypes[array_rand($memberTypes)],
                    'is_active'        => true,
                    'membership_start' => now()->subMonths(rand(1, 12)),
                    'membership_end'   => now()->addMonths(rand(6, 24)),
                ]
            );
        }

        $this->command->info('✅ Data dummy berhasil dibuat!');
        $this->command->info('👤 Admin: admin@perpustakaan.local / password123');
    }
}
