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
        // Schema::create('donor_types', function (Blueprint $table) {
        //     $table->uuid('donor_type_id')->primary();
        //     $table->string('title');
        //     $table->integer('type');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
        Schema::table('donor_types', function (Blueprint $table) {
            $table->uuid('donor_type_id')->primary();
            $table->string('title');
            $table->integer('type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('donor_types');
    }
};
