<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $conn = DB::connection('supabase');

        $items = [
            ['name' => 'Fiksi', 'description' => 'Buku fiksi'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku non-fiksi'],
            ['name' => 'Referensi', 'description' => 'Buku referensi'],
        ];

        $now = now();

        $toInsert = array_map(function ($item) use ($now) {
            return array_merge($item, [
                'slug' => Str::slug($item['name']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $items);

        $conn->table('categories')->insert($toInsert);
    }
}
