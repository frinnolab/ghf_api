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
        // Schema::table('company_infos', function (Blueprint $table) {
        //     // $table->uuid('id')->primary();
        //     $table->renameColumn('companyName', 'company_Name')->nullable();
        //     $table->renameColumn('companyAddress', 'company_Address')->nullable();
        //     $table->renameColumn('companyEmail', 'company_Email')->nullable();
        //     $table->renameColumn('companyMobile', 'company_Mobile')->nullable();
        //     $table->renameColumn('companyBiography', 'company_Biography')->nullable();
        //     $table->renameColumn('companyMission', 'company_Mission')->nullable();
        //     $table->renameColumn('companyVision', 'company_Vision')->nullable();
        //     $table->renameColumn('logoUrl', 'logo_Url')->nullable();
        //     $table->renameColumn('companyMobileTelephone', 'company_Mobile_Telephone')->nullable();
        //     $table->renameColumn('companyMobileAltenate', 'company_Mobile_Altenate')->nullable();
        //     $table->renameColumn('introVideoUrl', 'intro_VideoUrl')->nullable();
        //     //$table->timestamps();
        // });

        // Schema::table('company_infos', function (Blueprint $table) {
        //     $table->softDeletes();
        // });
        // Schema::table('company_infos', function (Blueprint $table) {
        //    $table->string('companyMobileTelephone')->nullable();
        //    $table->string('companyMobileAltenate')->nullable();
        //     // $table->softDeletes();
        // });

        Schema::table('company_infos', function (Blueprint $table) {
            $table->longText('company_Biography')->change()->nullable();
            $table->longText('company_Mission')->change()->nullable();
            $table->longText('company_Vision')->change()->nullable();
        });
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
