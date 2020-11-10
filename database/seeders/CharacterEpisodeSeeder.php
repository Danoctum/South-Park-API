<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CharacterEpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = Storage::get('episodes.json');
        $episodesArray = json_decode($json, true);

        foreach($episodesArray['episodes'] as $episode) {
            foreach($episode['characters'] as $characterId) {
                DB::table('character_episode')->insert([
                    'character_id' => $characterId,
                    'episode_id' => $episode['id'],
                ]);
            }
        }
    }
}
