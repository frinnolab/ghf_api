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
        // Schema::create('partners', function (Blueprint $table) {
        //     $table->uuid('partner_id')->primary();
        //     $table->string('logo_url')->default('');
        //     $table->string('name')->default('');
        //     $table->string('description')->default('');
        //     $table->integer('type')->default(0);//0=Partner, 1=Donor
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
        Schema::table('partners', function (Blueprint $table) {
            // $table->string('logo_url')->nullable()->change();
            // $table->string('name')->nullable()->change();
            // $table->string('description')->nullable()->change();
            // $table->integer('start_year')->nullable();
            // $table->integer('end_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('partners');
    }
};
