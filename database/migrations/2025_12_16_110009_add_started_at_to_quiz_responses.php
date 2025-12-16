<?php
// database/migrations/[timestamp]_add_started_at_to_quiz_responses.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_responses', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('percentage');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quiz_responses', function (Blueprint $table) {
            $table->dropColumn('started_at');
        });
    }
};