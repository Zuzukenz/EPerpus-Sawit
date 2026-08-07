<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Member;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Pemrograman & IT', 'slug' => 'pemrograman-it']);
        Category::create(['name' => 'Novel & Fiksi', 'slug' => 'novel-fiksi']);
        Category::create(['name' => 'Sains & Matematika', 'slug' => 'sains-matematika']);

        Member::factory(15)->create();

        Book::factory(30)->create();

        $member = Member::first();
        $book = Book::first();

        $borrowing = Borrowing::create([
            'member_id' => $member->id,
            'borrow_code' => 'TRX-' . rand(10000, 99999),
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'borrowed'
        ]);

        $borrowing->books()->attach($book->id, ['quantity' => 1]);
    }
}
