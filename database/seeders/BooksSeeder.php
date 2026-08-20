<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksSeeder extends Seeder
{
    public function run(): void
    {
        $conn = DB::connection('supabase');

        // find category ids by slug so seeder is robust
        $fiksi = $conn->table('categories')->where('slug', 'fiksi')->first();
        $nonfiksi = $conn->table('categories')->where('slug', 'non-fiksi')->first();

        $now = now();

        $toInsert = [
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'published_year' => 2008,
                'stock' => 5,
                'category_id' => $fiksi->id ?? 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt & David Thomas',
                'publisher' => 'Addison-Wesley',
                'published_year' => 1999,
                'stock' => 3,
                'category_id' => $fiksi->id ?? 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $conn->table('books')->insert($toInsert);
    }
}
