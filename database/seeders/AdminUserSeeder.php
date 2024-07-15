<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminEmail = Config::get('admin.admin_email');

        if (!$adminEmail) {
            throw new \Exception('admin email not set in config/admin.php');
        }
        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'username' => 'Admin',
                'password' => Hash::make('123456789'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
