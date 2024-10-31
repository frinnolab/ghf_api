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
        // Schema::create('careers', function (Blueprint $table) {
        //     $table->uuid('career_id')->primary();
        //     $table->string('position')->nullable();
        //     $table->string('description')->nullable();
        //     $table->smallInteger('career_type')->default(0); //Volunteering, Emloyment
        //     $table->string('requirements')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        Schema::table('careers', function (Blueprint $table) {
            // $table->uuid('career_id')->primary();
            // $table->string('position')->nullable();
            // $table->string('description')->nullable();
            // $table->smallInteger('career_validity')->default(0); //Closed = 0, Open = 1;
            // $table->smallInteger('career_type')->default(0); //Volunteering, Emloyment
            // $table->string('requirements')->nullable();
            // $table->timestamps();
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('careers');
    }
};
