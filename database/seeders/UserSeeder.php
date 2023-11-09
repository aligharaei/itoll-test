<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name' => 'company',
                'last_name' => 'martin',
                'mobile_number' => 9902042098,
                'password' => bcrypt(12345678),
                'type' => 0,
                'status' => 1,
            ],
            [
                'first_name' => 'Ali',
                'last_name' => 'Gharaei',
                'mobile_number' => 9221213114,
                'password' => bcrypt(12345678),
                'type' => 1,
                'status' => 1,
            ],

            [
                'first_name' => 'delivery',
                'last_name' => 'men',
                'mobile_number' => 9902042097,
                'password' => bcrypt(12345678),
                'type' => 2,
                'status' => 1,
            ],
        ];

        // Insert users with static data
        DB::table('users')->insert($users);
    }
}
