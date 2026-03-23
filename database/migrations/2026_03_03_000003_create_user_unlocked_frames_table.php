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
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('user_id');
            $table->unsignedBigInteger('frame_id');
            $table->date('unlocked_at');
            $table->timestamps();

            $table->unique(['user_id', 'frame_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('frame_id')->references('id')->on('avatar_frames')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_unlocked_frames');
    }
};
