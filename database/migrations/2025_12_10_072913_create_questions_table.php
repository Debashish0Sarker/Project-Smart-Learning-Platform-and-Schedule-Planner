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
    Schema::create('questions', function (Blueprint $table) {
        $table->id();

        // Question text
        $table->text('question_text');

        // MCQ, True/False, Short answer
        $table->enum('question_type', ['mcq', 'true_false', 'short_answer'])->default('mcq');

        // For MCQ or true/false
        $table->json('options')->nullable();

        // Array of correct answers (MCQ can have multiple)
        $table->json('correct_answers')->nullable();

        // Used by recommendation system & analytics
        $table->string('topic_tag');

        // Points for this question
        $table->integer('points')->default(1);

        $table->text('explanation')->nullable();

        // Relationship with quizzes table
        $table->foreignId('quiz_id')->constrained()->onDelete('cascade');

        // Question creator (teacher)
        $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
   {
    Schema::dropIfExists('questions');
    }
};
