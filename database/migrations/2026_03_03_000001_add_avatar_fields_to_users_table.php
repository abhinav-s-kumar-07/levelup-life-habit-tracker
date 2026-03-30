<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'avatar')) {
            $table->string('avatar')->default('avatar1.png')->after('password');
        }

        if (!Schema::hasColumn('users', 'equipped_frame_id')) {
            $table->foreignId('equipped_frame_id')
                  ->nullable()
                  ->constrained('avatar_frames')
                  ->nullOnDelete()
                  ->after('avatar');
        }
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'equipped_frame_id')) {
                $table->dropColumn('equipped_frame_id');
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};
