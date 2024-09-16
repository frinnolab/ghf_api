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

        $data = [$reportsType, $newsLetterType];

        DB::table('publication_types')->insert($data);
    }
}
