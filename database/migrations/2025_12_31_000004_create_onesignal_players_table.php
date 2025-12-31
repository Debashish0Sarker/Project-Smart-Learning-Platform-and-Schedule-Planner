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
        Schema::create('onesignal_players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('player_id')->unique();
            $table->string('platform')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onesignal_players');
    }
};
