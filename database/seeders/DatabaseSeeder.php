<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin EPerpus',
            'email' => 'admin@eperpus.local',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create Categories
        $categories = [
            [
                'name' => 'Fiksi',
                'slug' => 'fiksi',
                'description' => 'Buku cerita dan novel',
            ],
            [
                'name' => 'Non-Fiksi',
                'slug' => 'non-fiksi',
                'description' => 'Buku pengetahuan dan referensi',
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'description' => 'Buku tentang teknologi dan pemrograman',
            ],
            [
                'name' => 'Bisnis',
                'slug' => 'bisnis',
                'description' => 'Buku tentang bisnis dan entrepreneurship',
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Buku pelajaran dan akademik',
            ],
            [
                'name' => 'Seni & Budaya',
                'slug' => 'seni-budaya',
                'description' => 'Buku tentang seni dan budaya',
            ],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }

        echo "✅ Database seeded successfully!\n";
        echo "Admin Email: admin@eperpus.local\n";
        echo "Admin Password: password123\n";
    }
}
