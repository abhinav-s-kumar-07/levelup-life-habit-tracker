<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\PixelAssetController;
use App\Http\Controllers\ProfileCustomizationController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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
Route::get('/profile/customize', [ProfileCustomizationController::class, 'show']);
Route::post('/profile/avatar', [ProfileCustomizationController::class, 'updateAvatar']);
Route::post('/profile/frame', [ProfileCustomizationController::class, 'equipFrame']);

Route::get('/friends', [FriendController::class, 'index']);
Route::get('/friends/search', [FriendController::class, 'search']);
Route::post('/friends/request/{userId}', [FriendController::class, 'sendRequest']);
Route::post('/friends/accept/{friendshipId}', [FriendController::class, 'accept']);
Route::post('/friends/reject/{friendshipId}', [FriendController::class, 'reject']);
Route::post('/friends/remove/{friendshipId}', [FriendController::class, 'remove']);

Route::get('/leaderboard', [FriendController::class, 'leaderboard']);
Route::get('/feed', [FriendController::class, 'feed']);
Route::get('/u/{id}', [PublicProfileController::class, 'show']);
Route::get('/pixel/avatar/{filename}', [PixelAssetController::class, 'avatar']);
Route::get('/pixel/frame/{filename}', [PixelAssetController::class, 'frame']);

Route::get('/admin', [SuperAdminController::class, 'index']);
Route::get('/admin/frames', [SuperAdminController::class, 'frames']);
Route::post('/admin/frames/{id}', [SuperAdminController::class, 'updateFrame']);
Route::post('/admin/frames/{id}/toggle-manual', [SuperAdminController::class, 'toggleManual']);
Route::post('/admin/frame/unlock', [SuperAdminController::class, 'unlockFrameForUser']);
Route::post('/admin/frame/equip', [SuperAdminController::class, 'equipFrameForUser']);

if (app()->environment('local')) {
    Route::get('/init-db', function () {
        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--class' => 'SuperAdminSeeder']);

            return 'Database migrated and SuperAdmin seeded.';
        } catch (\Exception $e) {
            return 'Error: '.$e->getMessage();
        }
    });
}
