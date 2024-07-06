<?php

namespace Database\Seeders;

use App\Models\Profiles\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultProfileSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $adminProfile = [
            'profile_id'=>uuid_create(),
            'firstname'=>'GHF',
            'lastname'=>'admin',
            'email'=>'ghfadmin@ghfadmin.com',
            'hashed_password'=>password_hash('ghfadmin@ghfadmin', HASH_HMAC),
            'mobile'=>'',
            'roleType'=>-1,
        ];

        DB::table('profiles')->insert($adminProfile);
    }
}
