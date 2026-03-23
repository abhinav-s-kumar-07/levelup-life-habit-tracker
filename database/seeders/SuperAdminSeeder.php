<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'superadmin@leveluplife.local';

        $existing = DB::table('users')->where('email', $email)->first();

        if ($existing) {
            DB::table('users')->where('id', $existing->id)->update([
                'is_super_admin' => true,
                'updated_at' => now(),
            ]);
            return;
        }

        $userId = DB::table('users')->insertGetId([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => Hash::make('Admin@12345'),
            'avatar' => 'avatar1.png',
            'is_super_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
}
