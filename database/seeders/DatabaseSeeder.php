<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $conn = DB::connection('supabase');

        // Empty existing tables in a safe order and reset sequences.
        // TRUNCATE with RESTART IDENTITY and CASCADE will reset IDs and remove dependent rows.
        $conn->statement('TRUNCATE TABLE borrowing_book, borrowings, books, members, categories RESTART IDENTITY CASCADE');

        $this->call([
            CategoriesSeeder::class,
            MembersSeeder::class,
            BooksSeeder::class,
            BorrowingsSeeder::class,
        ]);
    }
}
