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
        // Schema::create('donations', function (Blueprint $table) {
        //     $table->uuid('donation_id')->primary();
        //     $table->string('email');
        //     $table->string('first_name');
        //     $table->string('last_name');
        //     $table->string('mobile');
        //     $table->decimal('amount_pledged');
        //     $table->integer('donor_currency_type');
        //     $table->integer('donor_type');
        //     $table->timestamps();
        // });
        // Schema::table('donations', function (Blueprint $table) {
        //     // $table->uuid('donation_id')->primary();
        //     // $table->string('email')->change()->nullable();
        //     // $table->string('first_name')->change()->nullable();
        //     // $table->string('last_name')->change()->nullable();
        //     // $table->string('mobile')->change()->nullable();
        //     // $table->decimal('amount_pledged')->change()->nullable();
        //     // $table->integer('donor_currency_type')->change()->default(0);
        //     // $table->integer('donor_type')->change()->default(0);
        //     $table->integer('donor_status_type')->default(0);
        //     // $table->string('description')->change()->nullable();
        //     // $table->string('company')->change()->nullable();
        //     //$table->softDeletes();
        // });

        Schema::table('donations', function (Blueprint $table) {
            $table->longText('description')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('donations');
    }
};
