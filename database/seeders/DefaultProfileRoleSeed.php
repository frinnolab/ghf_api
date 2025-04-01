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

        $employeeSeed = [
            "profile_role_id"=>uuid_create(),
            "name"=>"Employee",
            "type"=>3,
        ];
        $volunteerSeed = [
            "profile_role_id"=>uuid_create(),
            "name"=>"Volunteer",
            "type"=>4,
        ];
        $internSeed = [
            "profile_role_id"=>uuid_create(),
            "name"=>"Intern",
            "type"=>5,
        ];
        $boardSeed = [
            "profile_role_id"=>uuid_create(),
            "name"=>"BoardMember",
            "type"=>6,
        ];

        $seeds = [
            // $employeeSeed,
            // $volunteerSeed
            $internSeed,
            $boardSeed
        ];

        DB::table('profile_roles')->insert($seeds);
    }
}
