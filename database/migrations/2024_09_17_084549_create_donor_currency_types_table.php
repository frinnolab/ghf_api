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
        // Schema::create('donor_currency_types', function (Blueprint $table) {
        //     $table->uuid('donor_currency_id')->primary();
        //     $table->string('title')->nullable();
        //     $table->string('short_name')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
        // Schema::table('donor_currency_types', function (Blueprint $table) {
        //     $table->integer('type')->default(0);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('donor_currency_types');
    }
};
