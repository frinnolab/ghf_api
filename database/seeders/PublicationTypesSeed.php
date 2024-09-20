<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicationTypesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $reportsType = [
            "publish_type_id"=>uuid_create(),
            "title"=>"Report",
            "value"=>0
        ];

        $newsLetterType = [
            "publish_type_id"=>uuid_create(),
            "title"=>"Newsletter",
            "value"=>1
        ];

        $studentManualType = [
            "publish_type_id"=>uuid_create(),
            "title"=>"Student Manual",
            "value"=>2
        ];

        $data = [$studentManualType];

        DB::table('publication_types')->insert($data);
    }
}
