<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Episode;

class CharacterEpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $charactersPerEpisode = (object) [
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
                4,
            ],
            3 => [
                1,
                2,
                3,
                4,
                5,
            ],
        ];


        foreach($charactersPerEpisode as $episodeId=>$characterIds) {
            $episode = Episode::find($episodeId);
            $episode->characters()->attach($characterIds);
        }
    }
}
