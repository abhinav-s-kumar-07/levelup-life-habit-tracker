<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    if (Schema::hasTable('user_unlocked_frames')) {
        Schema::drop('user_unlocked_frames');
    }

    Schema::create('user_unlocked_frames', function (Blueprint $table) {
        $table->id();

        // Correct foreign keys
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('frame_id')->constrained('avatar_frames')->onDelete('cascade');

        $table->date('unlocked_at');
        $table->timestamps();

        $table->unique(['user_id', 'frame_id']);
    });
}
    public function down(): void
    {
        Schema::dropIfExists('user_unlocked_frames');
    }
};
