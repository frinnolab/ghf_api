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
        // Schema::create('career_applications', function (Blueprint $table) {
        //     $table->uuid('career_app_id')->primary();
        //     $table->string('email')->nullable();
        //     $table->string('firstname')->nullable();
        //     $table->string('lastname')->nullable();
        //     $table->string('mobile')->nullable();
        //     $table->string('biography')->nullable();
        //     $table->string('city')->nullable();
        //     $table->string('country')->nullable();
        //     $table->smallInteger('career_status')->nullable();
        //     $table->smallInteger('career_role_type')->default(3);
        //     $table->timestamps();
        // });
        Schema::table('career_applications', function (Blueprint $table) {
            // $table->uuid('career_app_id')->primary();
           // $table->string('career_id')->nullable();
            // $table->string('email')->nullable();
            // $table->string('firstname')->nullable();
            // $table->string('lastname')->nullable();
            // $table->string('mobile')->nullable();
            // $table->string('biography')->nullable();
            // $table->string('city')->nullable();
            // $table->string('country')->nullable();
            $table->string('avatar_url')->nullable();
            // $table->smallInteger('career_status')->nullable();
            // $table->smallInteger('career_role_type')->default(3);
            // $table->timestamps();
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('career_applications');
    }
};
