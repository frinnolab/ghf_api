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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('project_id')->primary();
            $table->string('programme_id')->nullable();
            $table->string('publisher_profile_id')->default('');
            $table->string('title')->default('');
            $table->string('description')->default('');
            $table->integer('regions_reached');
            $table->integer('districts_reached')->default(0);
            $table->integer('schools_reached')->default(0);
            $table->integer('students_reached')->default(0);
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        // Schema::table('projects', function (Blueprint $table) {
        //     $table->renameColumn('name', 'title')->default('');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('projects');
    }
};
