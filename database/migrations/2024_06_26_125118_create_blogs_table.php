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
        // Schema::create('blogs', function (Blueprint $table) {
        //     $table->uuid('blog_id')->primary();
        //     $table->string('thumbnail_url')->default('');
        //     $table->string('title')->default('');
        //     $table->string('description')->default('');
        //     $table->string('author_id');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
        // Schema::table('blogs', function (Blueprint $table) {
        //     //$table->uuid('blog_id')->primary();
        //     $table->string('thumbnail_url')->default('');
        //     // $table->string('title')->default('');
        //     // $table->string('description')->default('');
        //     // $table->string('author_id');
        //     // $table->timestamps();
        //     // $table->softDeletes();
        // });

        Schema::table('blogs', function (Blueprint $table) {
            $table->longText('description')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('blogs');
    }
};
