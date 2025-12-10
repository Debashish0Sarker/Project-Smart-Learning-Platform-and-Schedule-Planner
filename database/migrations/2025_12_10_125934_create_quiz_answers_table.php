<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
   {
    Schema::create('quiz_answers', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('response_id');   // belongs to quiz_responses
        $table->unsignedBigInteger('question_id');
        
        $table->text('answer_given')->nullable();    // student's given answer
        $table->boolean('is_correct')->nullable();   // null = subjective question

        $table->timestamps();

        // Foreign keys
        $table->foreign('response_id')->references('id')->on('quiz_responses')->onDelete('cascade');
        $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
