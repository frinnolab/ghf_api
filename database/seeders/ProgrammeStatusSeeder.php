<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgrammeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $completed = 
        [
            'status_id'=>uuid_create(),
            'name'=>"Completed",
            'type'=>0,
        ];

        $ongoing = 
        [
            'status_id'=>uuid_create(),
            'name'=>"Ongoing",
            'type'=>1,
        ];

        $statuses = [$completed, $ongoing];

        DB::table('programme_statuses')->insert($statuses);
    }
}
