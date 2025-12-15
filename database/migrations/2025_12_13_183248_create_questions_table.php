<?php
// database/migrations/2025_12_13_000006_create_questions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('teacher_id');
            $table->text('question_text');
            $table->string('question_type')->default('mcq');
            $table->text('options')->nullable();
            $table->text('correct_answers')->nullable();
            $table->string('topic_tag');
            $table->integer('points')->default(1);
            $table->text('explanation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};