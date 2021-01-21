<?php

use Illuminate\Database\Seeder;
use App\Players;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        factory(App\Players::class, 10000)->create();
        
        factory(App\GamePlay::class, 3835)->create(); 

        $this->call(GamesTableSeeder::class);
        // ->each(function ($user) {
        //     $user->posts()->save(factory(App\Post::class)->make());
        // });
    }
}
