<?php
// database/migrations/2023_12_01_000000_create_sections_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // Section name (e.g., "A", "B", "C")
            $table->integer('grade_level');      // 7, 8, 9, or 10
            $table->timestamps();                // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('sections');
    }
};