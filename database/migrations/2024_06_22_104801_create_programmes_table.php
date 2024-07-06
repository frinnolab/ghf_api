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
        // Schema::create('programmes', function (Blueprint $table) {
        //     $table->uuid('programme_id')->primary();
        //     $table->string('publisher_profile_id')->default('');
        //     $table->string('name')->default('');
        //     $table->string('description')->default('');
        //     $table->integer('status')->default(0);
        //     $table->dateTime('date_start')->nullable();
        //     $table->dateTime('date_end')->nullable();
        //     $table->softDeletes();
        //     $table->timestamps();
        // });

        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn('regions_reached');
            $table->dropColumn('districts_reached');
            $table->dropColumn('schools_reached');
            $table->dropColumn('students_reached');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('programmes');
    }
};
