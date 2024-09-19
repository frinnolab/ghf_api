<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonorStatusTypesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $notPaid = [
            "donor_status_id"=>uuid_create(),
            "title"=>"Not Paid",
            "type"=>0
        ];

        $paid = [
            "donor_status_id"=>uuid_create(),
            "title"=>"Paid",
            "type"=>1
        ];

        $seeds = [
            $notPaid,
            $paid
        ];

        DB::table('donor_statuses')->insert($seeds);
    }
}
