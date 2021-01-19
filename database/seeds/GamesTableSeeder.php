<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $games = [
            "Call of Duty", "Mortal Kombat", "FIFA", "Just Cause", "Apex Legend"
        ];

        $versions = ["2010","2011","2012","2013","2014","2015","2016","2017","2018","2019","2020"];

        foreach ($games as $key => $value){
            foreach($versions as $version){
                DB::table('games')->insert([
                    'name' => $value,
                    'version'=> $version,
                    'date_added'=> now(),
                ]);
            }
        }
    }
}
