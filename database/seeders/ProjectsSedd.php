<?php

namespace Database\Seeders;

use App\Models\Programmes\Programmes;
use App\Models\Projects\Project;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsSedd extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $newProject = [
            'programme_id' => uuid_create(),
            'publisher_profile_id' => "e50e0176-4d5d-482e-8158-381dc6baef77",
            'thumbnail_url' => "",
            'title' => "UWEZO PROGRAM",
            'description' => "",
            'status' => 1,
            'date_start' => date_create(),
            'date_end' => null,
        ];

        DB::table('programs')->insert($newProject);
        
    }
}
