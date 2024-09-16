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
        Schema::create('publication_types', function (Blueprint $table) {
            $table->uuid('publish_type_id')->primary();
            $table->string('title')->nullable();
            $table->integer('value')->default(0);//0:Report, 1:Newsletter
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('publication_types');
    }
};
