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
        Schema::create('partner_types', function (Blueprint $table) {
            $table->uuid('partner_type_id')->primary();
            $table->string('name')->default('');
            $table->integer('type')->default(0);//0=Partner, 1=Donor
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_types');
    }
};