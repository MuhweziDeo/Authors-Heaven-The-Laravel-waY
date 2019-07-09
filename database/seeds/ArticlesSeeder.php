<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = DB::table('users')->select('username', 'email as user_email', 'uuid')->get();
        $faker = Faker::create(); 
        foreach(range(1, 10) as $index ) {
        	DB::table('articles')->insert([
        		'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
	            'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
	            'body' => $faker->realText($maxNbChars = 200, $indexSize = 2),
	            'slug' => $faker->slug,
	            'author_uuid' =>  $users[rand(0,10)]->uuid,
	            'image' => $faker->imageUrl($width = 640, $height = 480),
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now()
        	]);
        }
    }
}
