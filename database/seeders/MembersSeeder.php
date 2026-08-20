<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembersSeeder extends Seeder
{
    public function run(): void
    {
        $conn = DB::connection('supabase');

        $now = now();

        $members = [
            [
                'nisn' => '1000000001',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.test',
                'address' => 'Jl. Merpati No.1',
                'phone' => '081234567890',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nisn' => '1000000002',
                'name' => 'Budi Santoso',
                'email' => 'budi@example.test',
                'address' => 'Jl. Kenanga No.2',
                'phone' => '081298765432',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nisn' => '1000000003',
                'name' => 'Rina Wijaya',
                'email' => 'rina@example.test',
                'address' => 'Jl. Melati No.3',
                'phone' => '081377788899',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nisn' => '1000000004',
                'name' => 'Agus Widodo',
                'email' => 'agus@example.test',
                'address' => 'Jl. Mawar No.4',
                'phone' => '081366655544',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nisn' => '1000000005',
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.test',
                'address' => 'Jl. Anggrek No.5',
                'phone' => '081355544433',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $conn->table('members')->insert($members);
    }
}
