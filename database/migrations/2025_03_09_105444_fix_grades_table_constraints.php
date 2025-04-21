<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, drop the foreign key constraints
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['subject_id']);
        });

        // Now drop the unique index
        Schema::table('grades', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'subject_id']);
        });

        // Re-add the foreign key constraints
        Schema::table('grades', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            // Add new unique constraint that includes period and period_type
            $table->unique(['student_id', 'subject_id', 'period', 'period_type'], 'grades_unique_per_period');
        });
    }

    public function down()
    {
        // First, drop the new unique constraint
        Schema::table('grades', function (Blueprint $table) {
            $table->dropUnique('grades_unique_per_period');
        });

        // Drop foreign key constraints
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['subject_id']);
        });

        // Re-add original unique constraint and foreign keys
        Schema::table('grades', function (Blueprint $table) {
            $table->unique(['student_id', 'subject_id']);
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }
};
