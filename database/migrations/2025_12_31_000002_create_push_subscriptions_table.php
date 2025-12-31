<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('endpoint');
            $table->string('p256dh')->nullable();
            $table->string('auth')->nullable();
            $table->string('content_encoding')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
}
