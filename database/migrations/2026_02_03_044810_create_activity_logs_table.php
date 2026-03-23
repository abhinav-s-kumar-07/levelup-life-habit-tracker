<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('activity_logs', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->id();

        // actor = who did the action (int because users.id is int)
        $table->integer('actor_user_id');

        // optional: who should see it (for now we show to friends + self using joins)
        $table->string('type', 30); // habit_done, friend_accepted, level_up etc.

        $table->string('title', 200);      // short message
        $table->string('subtitle', 200)->nullable(); // extra info
        $table->string('icon', 10)->nullable();      // emoji like ✅🔥🏆

        $table->timestamps();

        $table->index(['actor_user_id', 'created_at']);
        $table->foreign('actor_user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
