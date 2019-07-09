<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create(); 
        foreach (range(1, 10) as $index ) {
        	DB::table('users')->insert([
        		'username' => $faker->name,
	            'email' => $faker->email,
	            'password' => bcrypt('secret'),
	            'uuid' => Str::uuid()
        	]);
        }
    }
}
