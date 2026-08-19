<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teknologi = Category::where('name', 'Teknologi')->first();
        $fiksi = Category::where('name', 'Fiksi')->first();

        $books = [
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0132350884',
                'category_id' => $teknologi->id,
                'publisher' => 'Prentice Hall',
                'year' => 2008,
                'quantity' => 5,
                'description' => 'Panduan lengkap untuk menulis kode yang bersih dan mudah dipahami',
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'isbn' => '978-0201633610',
                'category_id' => $teknologi->id,
                'publisher' => 'Addison-Wesley',
                'year' => 1994,
                'quantity' => 3,
                'description' => 'Referensi tentang design patterns dalam pemrograman',
            ],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'isbn' => '978-9793069625',
                'category_id' => $fiksi->id,
                'publisher' => 'Bentang Pustaka',
                'year' => 2005,
                'quantity' => 7,
                'description' => 'Novel tentang persahabatan dan pendidikan di sebuah sekolah kecil',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
