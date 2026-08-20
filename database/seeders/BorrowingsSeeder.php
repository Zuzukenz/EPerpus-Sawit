<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BorrowingsSeeder extends Seeder
{
    public function run(): void
    {
        $conn = DB::connection('supabase');

        $now = now();

        // pick first member and first book (if exist)
        $member = $conn->table('members')->first();
        $book = $conn->table('books')->first();

        if (!$member || !$book) {
            // nothing to do
            return;
        }

        $borrowCode = 'TRX-' . rand(10000, 99999);

        $borrowingId = $conn->table('borrowings')->insertGetId([
            'member_id' => $member->id,
            'borrow_code' => $borrowCode,
            'borrow_date' => $now->toDateString(),
            'due_date' => $now->addDays(7)->toDateString(),
            'status' => 'borrowed',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $conn->table('borrowing_book')->insert([
            'borrowing_id' => $borrowingId,
            'book_id' => $book->id,
            'quantity' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
