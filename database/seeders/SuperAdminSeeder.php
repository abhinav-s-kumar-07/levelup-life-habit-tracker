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
        if (!Schema::hasTable('users')) {
            $this->command->error('Users table does not exist. Run migrations first.');
            return;
        }

        $email = env('SUPERADMIN_EMAIL', 'superadmin@leveluplife.local');
        $password = env('SUPERADMIN_PASSWORD', 'Admin@12345');
        $name = env('SUPERADMIN_NAME', 'Super Admin');
        $avatar = env('SUPERADMIN_AVATAR', 'avatar1.png');
        $passwordHash = Hash::make($password);

        $existing = DB::table('users')->where('email', $email)->first();

        if ($existing) {
            DB::table('users')->where('id', $existing->id)->update([
                'name' => $name,
                'password' => $passwordHash,
                'avatar' => $avatar,
                'is_super_admin' => true,
                'updated_at' => now(),
            ]);
            $userId = $existing->id;
        } else {
            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'avatar' => $avatar,
                'is_super_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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

        $this->command->info("Super admin ready: {$email}");
    }
}
