<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabitController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'registerUser']);

Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'loginUser']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', [HabitController::class, 'dashboard'])->name('dashboard');
Route::get('/add-habit', [HabitController::class, 'create']);
Route::post('/add-habit', [HabitController::class, 'store']);
Route::post('/complete/{id}', [HabitController::class, 'complete']);
Route::post('/habit/delete/{id}', [HabitController::class, 'delete']);
Route::get('/habit/edit/{id}', [HabitController::class, 'edit']);
Route::post('/habit/update/{id}', [HabitController::class, 'update']);
Route::get('/profile', [AuthController::class, 'profile']);
Route::post('/profile/name', [AuthController::class, 'updateName']);
Route::post('/profile/password', [AuthController::class, 'updatePassword']);
use App\Http\Controllers\FriendController;

Route::get('/friends', [FriendController::class, 'index']);
Route::get('/friends/search', [FriendController::class, 'search']);

Route::post('/friends/request/{userId}', [FriendController::class, 'sendRequest']);
Route::post('/friends/accept/{friendshipId}', [FriendController::class, 'accept']);
Route::post('/friends/reject/{friendshipId}', [FriendController::class, 'reject']);
Route::post('/friends/remove/{friendshipId}', [FriendController::class, 'remove']);

Route::get('/leaderboard', [FriendController::class, 'leaderboard']);
Route::get('/feed', [FriendController::class, 'feed']);
//Route::view('/preview', 'preview');


use Illuminate\Support\Facades\Artisan;

Route::get('/init-db', function () {
    try {
        // Run migrations
        Artisan::call('migrate', ['--force' => true]);

        // Seed Super Admin
        Artisan::call('db:seed', ['--class' => 'SuperAdminSeeder']);

        return "✅ Database migrated and SuperAdmin seeded!";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

