<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
    
        DB::table('admin_users')->insert([
            'first_name' => $faker->firstname,
            'last_name' => $faker->lastname,
            'email' => $faker->safeEmail,
            'password' => Hash::make('password'), //str_random(8)
            'role'=> 'SUPER_ADMIN'
        ]);
    }
}
