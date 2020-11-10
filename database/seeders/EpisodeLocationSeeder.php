<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EpisodeLocationSeeder extends Seeder
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
            foreach($episode['locations'] as $locationId) {
                DB::table('episode_location')->insert([
                    'location_id' => $locationId,
                    'episode_id' => $episode['id'],
                ]);
            }
        }
    }
}
