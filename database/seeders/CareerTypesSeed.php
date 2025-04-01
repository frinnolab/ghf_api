<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CareerTypesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // $careerVol = [
        //     'career_type_id'=>uuid_create(),
        //     'title'=>'Volunteer',
        //     'type'=>0,
        // ];

        // $careerEmp = [
        //     'career_type_id'=>uuid_create(),
        //     'title'=>'Employment',
        //     'type'=>1,
        // ];

        $careerIntern = [
            'career_type_id'=>uuid_create(),
            'title'=>'Internship',
            'type'=>2,
        ];

        $cts = [
            // $careerVol,
            // $careerEmp
            $careerIntern
        ];


        DB::table('career_types')->insert($cts);

        
    }
}
