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
        // In migration file
Schema::create('subject_groups', function (Blueprint $table) {
    $table->id();
    $table->string('grade_level');
    $table->string('strand');
    $table->string('semester');
    $table->timestamps();
});

Schema::table('subjects', function (Blueprint $table) {
    $table->foreignId('subject_group_id')->nullable()->constrained();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_groups');
    }
};
