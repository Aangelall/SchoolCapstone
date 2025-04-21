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
        Schema::create('strands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Add the strand_id foreign key to the sections table
        Schema::table('sections', function (Blueprint $table) {
            if (!Schema::hasColumn('sections', 'strand_id')) {
                $table->foreignId('strand_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            if (Schema::hasColumn('sections', 'strand_id')) {
                $table->dropForeign(['strand_id']);
                $table->dropColumn('strand_id');
            }
        });
        
        Schema::dropIfExists('strands');
    }
};
