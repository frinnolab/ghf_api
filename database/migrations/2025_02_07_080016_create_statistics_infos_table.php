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
        Schema::create('statistics_infos', function (Blueprint $table) {
            $table->uuid('stat_id')->primary();
            $table->integer('regions_reached')->default(0);
            $table->integer('districts_reached')->default(0);
            $table->integer('students_impacted')->default(0);
            $table->integer('schools_reached')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('statistics_infos');
    }
};
