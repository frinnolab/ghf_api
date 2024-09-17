<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonorCurrencyTypesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $usd = [
            "donor_currency_id" => uuid_create(),
            "title" => "United States Dollar",
            "short_name" => "USD",
            "type" => 1,
        ];

        $gbp = [
            "donor_currency_id" => uuid_create(),
            "title" => "Pound Sterling",
            "short_name" => "GBP",
            "type" => 2,
        ];

        $tzs = [
            "donor_currency_id" => uuid_create(),
            "title" => "Tanzanian Shillings",
            "short_name" => "TZS",
            "type" => 0,
        ];

        $currencies = [
            $usd, $gbp, $tzs
        ];


        DB::table('donor_currency_types')->insert($currencies);
    }
}
