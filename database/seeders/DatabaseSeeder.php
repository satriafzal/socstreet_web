<?php

namespace Database\Seeders;

use App\Models\FieldSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Admin Account
        User::updateOrCreate(
            ['email' => 'adminsocer@gmail.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'role_id' => 1,
                'must_change_password' => false,
            ]
        );

        // Default Demo Customer Account for immediate testing
        User::updateOrCreate(
            ['email' => 'customer@socstreet.test'],
            [
                'username' => 'customer1',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'role_id' => 2,
                'must_change_password' => false,
            ]
        );

        // Default Field Settings
        FieldSetting::set('arena_name', 'Socstreet Main Arena', 'Nama Lapangan Utama');
        FieldSetting::set('base_price', '300000', 'Tarif reguler per sesi 2 jam (08:00 - 18:00)');
        FieldSetting::set('prime_price', '350000', 'Tarif prime time per sesi 2 jam (18:00 - 22:00)');
        FieldSetting::set('operating_hours', '08:00 - 22:00', 'Jam operasional lapangan');
    }
}
