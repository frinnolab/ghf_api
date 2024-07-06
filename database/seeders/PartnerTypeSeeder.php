<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $partnerType = [
            'partner_type_id'=>uuid_create(),
            'name'=>'PARTNER',
            'type'=>0
        ];
        
        $donorType = [
            'partner_type_id'=>uuid_create(),
            'name'=>'DONOR',
            'type'=>1
        ];
        $partners = [$partnerType, $donorType];

        DB::table('partner_types')->insert($partners);
    }
}
