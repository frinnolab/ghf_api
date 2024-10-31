<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CareerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    //Pending = 0 | Denied = 1 | Accepted = 2

    public function run(): void
    {
        //
        //Pending = 0 | Denied = 1 | Accepted = 2


        $cT0 = [
            'career_status_id' => uuid_create(),
            'title' => 'Pending',
            'type' => 0,
            'created_at' => new DateTime()
        ];

        $cT1 = [
            'career_status_id' => uuid_create(),
            'title' => 'Denied',
            'type' => 1,
            'created_at' => new DateTime()
        ];

        $cT2 = [
            'career_status_id' => uuid_create(),
            'title' => 'Accepted',
            'type' => 2,
            'created_at' => new DateTime()
        ];

        $cts = [
            $cT0,
            $cT1,
            $cT2
        ];

        DB::table('career_statuses')->insert($cts);
    }
}
