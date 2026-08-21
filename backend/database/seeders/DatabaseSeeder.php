<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DeduplicationSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create only the Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create Default Deduplication Settings
        if (DeduplicationSetting::count() === 0) {
            DeduplicationSetting::create([
                'ignore_hyphens' => true,
                'ignore_spaces' => true,
                'ignore_special_characters' => true,
                'ignore_leading_zeros' => false,
                'fuzzy_match_threshold' => 85,
            ]);
        }
    }
}
