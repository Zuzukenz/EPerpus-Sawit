<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use Carbon\Carbon;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'membership_number' => 'MEM001',
                'join_date' => Carbon::now()->subMonths(6),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'phone' => '081234567891',
                'address' => 'Jl. Ahmad Yani No. 456, Bandung',
                'membership_number' => 'MEM002',
                'join_date' => Carbon::now()->subMonths(3),
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad@example.com',
                'phone' => '081234567892',
                'address' => 'Jl. Sudirman No. 789, Surabaya',
                'membership_number' => 'MEM003',
                'join_date' => Carbon::now()->subMonth(),
            ],
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}
