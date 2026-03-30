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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();

        // FIXED: use foreignId
        $table->foreignId('actor_user_id')
              ->constrained('users')
              ->onDelete('cascade');

        $table->string('type', 30);
        $table->string('title', 200);
        $table->string('subtitle', 200)->nullable();
        $table->string('icon', 10)->nullable();

        $table->timestamps();

        $table->index(['actor_user_id', 'created_at']);
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
