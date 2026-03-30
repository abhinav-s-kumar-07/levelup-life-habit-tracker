<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avatar_frames', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type'); // png | css
            $table->string('asset')->nullable(); // png filename or css token
            $table->string('unlock_type'); // xp | habit_streak | level | manual
            $table->unsignedBigInteger('requirement_value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avatar_frames');
    }
};
