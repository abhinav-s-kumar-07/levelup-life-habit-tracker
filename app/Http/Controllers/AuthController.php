<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Services\FrameUnlockService;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Insert user into 'users' table
        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_super_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create rewards row safely if table exists
        if (Schema::hasTable('rewards')) {
            DB::table('rewards')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'points' => 0,
                    'level' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // Unlock frames if service exists
        FrameUnlockService::checkAndUnlock($userId);

        // Log in user via session
        session([
            'user_id' => $userId,
            'is_super_admin' => false,
        ]);

        return redirect('/dashboard');
    }

    /**
     * Show login form
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->ensureConfiguredSuperAdmin($request->email);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password'])->withInput();
        }

        // Log in user via session
        session([
            'user_id' => $user->id,
            'is_super_admin' => (bool) ($user->is_super_admin ?? false),
        ]);

        // Ensure rewards row exists safely
        if (Schema::hasTable('rewards')) {
            DB::table('rewards')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'points' => 0,
                    'level' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        FrameUnlockService::checkAndUnlock((int) $user->id);

        return redirect('/dashboard');
    }

    private function ensureConfiguredSuperAdmin(string $email): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        $superAdminEmail = env('SUPERADMIN_EMAIL', 'superadmin@leveluplife.local');
        if (strcasecmp($email, $superAdminEmail) !== 0) {
            return;
        }

        $password = env('SUPERADMIN_PASSWORD', 'Admin@12345');
        $name = env('SUPERADMIN_NAME', 'Super Admin');
        $avatar = env('SUPERADMIN_AVATAR', 'avatar1.png');
        $passwordHash = Hash::make($password);

        $existing = DB::table('users')->where('email', $superAdminEmail)->first();

        if ($existing) {
            DB::table('users')->where('id', $existing->id)->update([
                'name' => $name,
                'password' => $passwordHash,
                'avatar' => $avatar,
                'is_super_admin' => true,
                'updated_at' => now(),
            ]);

            if (Schema::hasTable('rewards')) {
                $hasRewards = DB::table('rewards')->where('user_id', $existing->id)->exists();
                if (!$hasRewards) {
                    DB::table('rewards')->insert([
                        'user_id' => $existing->id,
                        'points' => 0,
                        'level' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return;
        }

        $userId = DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $superAdminEmail,
            'password' => $passwordHash,
            'avatar' => $avatar,
            'is_super_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (Schema::hasTable('rewards')) {
            DB::table('rewards')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'points' => 0,
                    'level' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        session()->forget('user_id');
        session()->forget('is_super_admin');
        session()->flush();

        return redirect('/login');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        if (!session('user_id')) return redirect('/login');

        $user = DB::table('users')->where('id', session('user_id'))->first();

        if (!$user) {
            session()->forget('user_id');
            return redirect('/login');
        }

        $badges = DB::table('user_badges')
            ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
            ->where('user_badges.user_id', session('user_id'))
            ->select('badges.name', 'badges.description', 'user_badges.earned_at')
            ->get();

        return view('profile', compact('user', 'badges'));
    }

    /**
     * Update user name
     */
    public function updateName(Request $request)
    {
        if (!session('user_id')) return redirect('/login');

        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        DB::table('users')
            ->where('id', session('user_id'))
            ->update([
                'name' => $request->name,
                'updated_at' => now(),
            ]);

        return redirect('/profile')->with('success', 'Name updated successfully.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        if (!session('user_id')) return redirect('/login');

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = DB::table('users')->where('id', session('user_id'))->first();
        if (!$user) {
            session()->forget('user_id');
            return redirect('/login');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect('/profile')->with('error', 'Current password is incorrect.');
        }

        DB::table('users')
            ->where('id', session('user_id'))
            ->update([
                'password' => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        return redirect('/profile')->with('success', 'Password updated successfully.');
    }
}
