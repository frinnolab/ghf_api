<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonorTypesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $localDonor  = [
            "donor_type_id" => uuid_create(),
            "title" => "LOCAL DONOR",
            "type" => 0
        ];

        $foreignDonor  = [
            "donor_type_id" => uuid_create(),
            "title" => "FOREIGN DONOR",
            "type" => 1
        ];

        $donorTypes = [$localDonor, $foreignDonor];

        DB::table('donor_types')->insert($donorTypes);
    }
}
