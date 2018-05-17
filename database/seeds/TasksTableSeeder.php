<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for($i = 0; $i < 10; $i++){
            DB::table('tasks')->insert([
                'name' => $faker->sentence(2),
                'description' => $faker->sentence(8),
                'user_id' => 1
            ]);
        }
    }
}
