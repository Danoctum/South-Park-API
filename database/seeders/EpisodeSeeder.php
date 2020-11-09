<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $episodes = [
            [
                'name' => 'Cartman Gets an Anal Probe',
                'season' => 1,
                'episode' => 1,
                'written_by' => 'Trey Parker',
                'air_date' => '1997-08-13',
            ],
            [
                'name' => 'Weight Gain 4000',
                'season' => 1,
                'episode' => 2,
                'written_by' => 'Trey Parker',
                'air_date' => '1997-08-20',
            ],
            [
                'name' => 'Volcano',
                'season' => 1,
                'episode' => 3,
                'written_by' => 'Trey Parker',
                'air_date' => '1997-08-27',
            ],
        ];


        foreach($episodes as $episode) {
            DB::table('episodes')->insert($episode);
        }
    }
}
