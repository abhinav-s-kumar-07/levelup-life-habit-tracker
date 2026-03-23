<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\FrameUnlockService;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerUser(Request $request)
    {
        // Validate
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Insert user (IMPORTANT: timestamps)
        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_super_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create rewards row if not exists
        $exists = DB::table('rewards')->where('user_id', $userId)->exists();
        if (!$exists) {
            DB::table('rewards')->insert([
                'user_id' => $userId,
                'points' => 0,
                'level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        FrameUnlockService::checkAndUnlock($userId);

        // ✅ Set session login
        session([
            'user_id' => $userId,
            'is_super_admin' => false,
        ]);

        return redirect('/dashboard');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // ✅ Set session login
            session([
                'user_id' => $user->id,
                'is_super_admin' => (bool) ($user->is_super_admin ?? false),
            ]);

            // Make sure rewards row exists
            $exists = DB::table('rewards')->where('user_id', $user->id)->exists();
            if (!$exists) {
                DB::table('rewards')->insert([
                    'user_id' => $user->id,
                    'points' => 0,
                    'level' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            FrameUnlockService::checkAndUnlock((int) $user->id);

            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password'])->withInput();
    }

    public function logout()
    {
        session()->forget('user_id');
        session()->flush();
        return redirect('/login');
    }
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

    // Verify current password
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
