<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure the 'users' table exists
        if (!Schema::hasTable('users')) {
            $this->command->error('Users table does not exist. Run migrations first.');
            return;
        }

        $email = 'superadmin@leveluplife.local';

        $existing = DB::table('users')->where('email', $email)->first();

        if ($existing) {
            // Update existing user as super admin
            DB::table('users')->where('id', $existing->id)->update([
                'is_super_admin' => true,
                'updated_at' => now(),
            ]);
            $userId = $existing->id;
        } else {
            // Create new super admin
            $userId = DB::table('users')->insertGetId([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make('Admin@12345'), // default password
                'avatar' => 'avatar1.png',
                'is_super_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert default reward if 'rewards' table exists
        if (Schema::hasTable('rewards')) {
            $hasRewards = DB::table('rewards')->where('user_id', $userId)->exists();
            if (!$hasRewards) {
                DB::table('rewards')->insert([
                    'user_id' => $userId,
                    'points' => 0,
                    'level' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Super admin seeded successfully!');
    }
}