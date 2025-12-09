<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_resources', function (Blueprint $table) {
            $table->id();
            $table->string('course_code');
            $table->enum('type', ['pdf', 'video', 'image']);
            $table->string('title');
             $table->text('url')->nullable();  // FIX: Made nullable
            $table->string('file_path')->nullable(); 
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('course_code')->references('code')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_resources');
    }
};