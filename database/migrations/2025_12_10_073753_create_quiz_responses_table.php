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
    Schema::create('quiz_responses', function (Blueprint $table) {
        $table->id();

        // Student who attempted
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // Quiz they attempted
        $table->foreignId('quiz_id')->constrained()->onDelete('cascade');

        // Store all answers in JSON:
        // [
        //   { "question_id": 5, "answer": ["A"] },
        //   { "question_id": 7, "answer": "Subjective long text" }
        // ]
        $table->json('answers');

        // Auto-calculated score for objective questions
        $table->integer('score')->default(0);

        // Percentage = score / total_points
        $table->float('percentage')->default(0);

        // Attempts timestamps
        $table->timestamp('submitted_at')->nullable();

        // Whether teacher has graded the subjective parts
        $table->boolean('is_checked')->default(false);

        $table->timestamps();
    });
 }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('quiz_responses');
    }
};
