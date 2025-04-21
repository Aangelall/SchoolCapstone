<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->decimal('grade', 5, 2);
            $table->timestamps();

            // Ensure a student can only have one grade per subject
            $table->unique(['student_id', 'subject_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
};
