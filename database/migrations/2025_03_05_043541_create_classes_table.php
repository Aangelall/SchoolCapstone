<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('level_type'); // 'junior' or 'senior'
            $table->integer('year_level'); // 7-10 for junior, 11-12 for senior
            $table->string('section');
            $table->string('strand')->nullable(); // For senior high only
            $table->integer('semester')->nullable(); // For senior high only
            $table->foreignId('adviser_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
