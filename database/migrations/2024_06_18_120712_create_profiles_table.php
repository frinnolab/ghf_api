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
        // Schema::create('profiles', function (Blueprint $table) {
        //     $table->uuid('profile_id')->primary();
        //     $table->string('email')->default('');
        //     $table->string('hashed_password')->default('');
        //     $table->string('firstname')->default('');
        //     $table->string('mobile')->default('');
        //     $table->integer('roleType')->default(0);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
        Schema::table('profiles', function (Blueprint $table) {
            // $table->renameColumn('profile_pic_url','avatar_url')->nullable();
            // $table->string('position')->change()->nullable();
            // $table->string('firstname')->change()->nullable();
            // $table->string('lastname')->change()->nullable();
            // $table->string('mobile')->change()->nullable();
            // $table->string('email')->change()->nullable();
            // $table->string('avatar_url')->change()->nullable();
            $table->string('biography')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('profiles');
    }
};
