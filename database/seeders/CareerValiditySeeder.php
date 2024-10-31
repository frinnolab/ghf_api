<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CareerValiditySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Closed = 0, Open = 1;

        //
        $cv0 = [
            'career_valid_id' => uuid_create(),
            'title' => 'Closed',
            'type' => 0,
            'created_at'=> new DateTime()
        ];

        $cv1 = [
            'career_valid_id' => uuid_create(),
            'title' => 'Open',
            'type' => 1,
            'created_at'=> new DateTime()
        ];

        $cvd = [
            $cv0,
            $cv1
        ];

        DB::table('career_validities')->insert($cvd);
    }
}
