<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
     protected $signature = 'admin:create 
                            {--name= : Admin name} 
                            {--username= : Admin username} 
                            {--password= : Admin password}';

    protected $description = 'Create a new admin user with linked User record';

    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('Enter admin name');
        $username = $this->option('username') ?? $this->ask('Enter admin username');
        $password = $this->option('password') ?? $this->secret('Enter admin password');

        // Validate input
        if (empty($name) || empty($username) || empty($password)) {
            $this->error('All fields are required!');
            return;
        }

        // Check if user already exists
        if (User::where('username', $username)->exists()) {
            $this->error('User with this username already exists!');
            return;
        }

        // Create User first
        $user = User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'user_type' => 'admin',
        ]);

        // Then create Admin record
        Admin::create([
            'user_id' => $user->id,
            'name' => $name,
            'username' => $username,
            'password' => $password,
        ]);

        $this->info('Admin user created successfully!');
    }
}
