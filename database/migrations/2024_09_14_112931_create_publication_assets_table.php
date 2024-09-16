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
        // Schema::create('publication_assets', function (Blueprint $table) {
        //     $table->uuid('publish_asset_id')->primary();
        //     $table->string('publish_id')->nullable();
        //     $table->string('asset_url')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });


        Schema::table('publication_assets', function (Blueprint $table) {
            $table->string('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('publication_assets');
    }
};
