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
        //
        Schema::create('impacts', function (Blueprint $table) {
            $table->uuid('impact_id')->primary();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('school_name')->nullable();
            $table->string('school_region')->nullable();
            $table->string('school_district')->nullable();
            $table->integer('student_girls')->default(0);
            $table->integer('student_boys')->default(0);
            $table->integer('student_total')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
