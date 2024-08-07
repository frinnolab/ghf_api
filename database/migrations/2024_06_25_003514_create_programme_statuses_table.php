<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * ProgStatus = [
     * "Ongoing"=1,
     * "Completed"=0
     * ]
     */
    public function up(): void
    {
        // Schema::create('programme_statuses', function (Blueprint $table) {
        //     $table->uuid('status_id')->primary();
        //     $table->string('name')->default('');
        //     $table->integer('type')->default(0);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        Schema::rename('programme_statuses', 'project_status');
        
        // Schema::table('programme_statuses', function (Blueprint $table) {
        //     $table->uuid('status_id')->primary();
        //     $table->string('name')->default('');
        //     $table->integer('type')->default(0);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('programme_statuses');
    }
};
