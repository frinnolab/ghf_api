<?php

namespace Database\Seeders;

use App\Models\Settings\CompanyInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyInfoSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('company_infos')->insert([
            'id' => uuid_create(),
            'companyName' => 'GREAT HOPE FOUNDATION',
            'companyAddress' => 'P.O.BOX 2466 DSM',
            'companyEmail' => 'greathopefoundation@gmail.com',
            'companyMobile' =>  json_encode([
                '+255 764 977 365','+255 783 672 512'
            ]),
        ]);
    }
}
