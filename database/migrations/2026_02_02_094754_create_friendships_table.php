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
    Schema::create('friendships', function (Blueprint $table) {
        $table->id();

        // IMPORTANT: users.id is SIGNED int(11), so use integer()
        $table->integer('requester_id');
        $table->integer('addressee_id');

        $table->enum('status', ['pending', 'accepted'])->default('pending');

        $table->timestamps();

        // Prevent duplicate requests in same direction
        $table->unique(['requester_id', 'addressee_id']);

        // Foreign keys
        $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('addressee_id')->references('id')->on('users')->onDelete('cascade');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};
