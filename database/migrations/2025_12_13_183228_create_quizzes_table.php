<?php
// database/migrations/2025_12_13_000005_create_quizzes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('difficulty')->default('medium');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher_id');
            $table->boolean('is_published')->default(false);
            $table->timestamp('due_date')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('total_points')->default(0);
            $table->integer('attempts_allowed')->default(1);
            $table->string('topic_tag')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};