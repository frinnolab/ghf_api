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
        // Schema::create('teams', function (Blueprint $table) {
        //     $table->uuid('team_id')->primary();
        //     $table->string('name')->nullable();
        //     $table->integer('total_members')->nullable()->default(0);
        //     $table->softDeletes();
        //     $table->timestamps();
        // });

        // Schema::table('teams', function (Blueprint $table) {
        //     $table->dropColumn('id');
        // });

        Schema::table('teams', function (Blueprint $table) {
            $table->boolean('is_main_board')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('teams');
    }
};
