<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     /**
      * ROLES = 
      [
        SuperAdmin => -1,
        Admin = 1
        User = 0,
        
      ]
      */
    public function up(): void
    {
        // Schema::create('profile_roles', function (Blueprint $table) {
        //     $table->uuid('profile_role_id')->primary();
        //     $table->string('name')->default('');
        //     $table->integer('type')->default(0);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
        Schema::table('profile_roles', function (Blueprint $table) {
            //$table->uuid('profile_role_id')->primary();
            $table->string('name')->change()->nullable();
            //$table->integer('type')->change()->nullable();
            //$table->timestamps()->change()->nullable();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('profile_roles');
    }
};
