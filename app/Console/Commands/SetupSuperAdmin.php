<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetupSuperAdmin extends Command
{
    protected $signature = 'setup:super-admin';
    protected $description = 'Set up the super admin account';

    public function handle()
{
    $email = 'mhrid19@gmail.com';
    $user = User::where('email', $email)->first();

    if (!$user) {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'username' => 'superadmin', // Tambahkan ini
            'password' => bcrypt('password'), // Ganti 'password' dengan password yang Anda inginkan
            'email_verified_at' => now(),
        ]);
        $this->info('New super-admin user created.');
    }

    $user->role = 'super-admin';
    $user->save();

    $this->info('Super admin account set up successfully');
}
}