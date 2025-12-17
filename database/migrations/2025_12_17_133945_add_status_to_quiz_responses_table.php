<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_responses', 'status')) {
                $table->enum('status', ['pending', 'submitted', 'graded', 'late'])
                      ->default('pending')
                      ->after('percentage');
            }
            
            if (!Schema::hasColumn('quiz_responses', 'feedback')) {
                $table->text('feedback')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quiz_responses', function (Blueprint $table) {
            $table->dropColumn(['status', 'feedback']);
        });
    }
};