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
        // Schema::create('company_infos', function (Blueprint $table) {
        //     $table->uuid('id')->primary();
        //     $table->string('companyName')->nullable();
        //     $table->string('companyAddress')->nullable();
        //     $table->string('companyEmail')->nullable();
        //     $table->string('companyMobile')->nullable();
        //     $table->string('companyBiography')->nullable();
        //     $table->string('companyMission')->nullable();
        //     $table->string('companyVision')->nullable();
        //     $table->string('logoUrl')->nullable();
        //     $table->string('introVideoUrl')->nullable();
        //     $table->timestamps();
        // });

        // Schema::table('company_infos', function (Blueprint $table) {
        //     $table->softDeletes();
        // });
        // Schema::table('company_infos', function (Blueprint $table) {
        //    $table->string('companyMobileTelephone')->nullable();
        //    $table->string('companyMobileAltenate')->nullable();
        //     // $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('company_infos');
        //Schema::dropIfExists('about_infos');
    }
};
