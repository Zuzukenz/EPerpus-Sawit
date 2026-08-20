<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('supabase')->table('categories')->insert([
            ['name' => 'Fiksi', 'description' => 'Buku fiksi'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku non-fiksi'],
            ['name' => 'Referensi', 'description' => 'Buku referensi'],
        ]);
    }
}