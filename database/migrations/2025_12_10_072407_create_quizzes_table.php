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
    Schema::create('quizzes', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();

        $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');

        // Course → quizzes relationship
        $table->foreignId('course_id')->constrained()->onDelete('cascade');

        // Teacher who created the quiz
        $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');

        $table->boolean('is_published')->default(false);
        $table->timestamp('due_date')->nullable();

        // Time allowed for quiz
        $table->integer('duration_minutes')->nullable();

        // Final score out of X points
        $table->integer('total_points')->default(0);

        // How many attempts a student can take
        $table->integer('attempts_allowed')->default(1);

        $table->timestamps();
    });
   }


    /**
     * Reverse the migrations.
     */
    public function down(): void
   {
    Schema::dropIfExists('quizzes');
    }
};
