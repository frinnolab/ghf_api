<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DefaultProfileRoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $SuperAdminSeed = [
            "profile_role_id"=>uuid_create(),
            "name"=>"SuperAdmin",
            "type"=>-1,
        ];

        $AdminSeed = [
            "profile_role_id"=>uuid_create(),
            "name"=>"Admin",
            "type"=>1,
        ];

        $GuestSeed = [
            "profile_role_id"=>uuid_create(),
            "name"=>"User",
            "type"=>0,
        ];
        
        $alumniSeed = [
            "profile_role_id"=>uuid_create(),
            "name"=>"Alumnae",
            "type"=>2,
        ];

        $seeds = [
            $alumniSeed
        ];

        DB::table('profile_roles')->insert($seeds);
    }
}
