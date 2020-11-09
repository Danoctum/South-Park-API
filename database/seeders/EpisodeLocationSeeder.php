<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Episode;

class EpisodeLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locationsPerEpisode = (object) [
            1 => [
                1,
                2,
                3,
                4,
            ],
            2 => [
                1,
                2,
                3,
                5,
                6,
                7,
                8,
                9,
            ],
            3 => [
                3,
                6,
                10,
                11,
                12
            ],
        ];


        foreach($locationsPerEpisode as $episodeId=>$locationIds) {
            $episode = Episode::find($episodeId);
            $episode->locations()->attach($locationIds);
        }
    }
}
