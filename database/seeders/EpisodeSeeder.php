<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EpisodeSeeder extends Seeder
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
            unset($episode['characters']);
            unset($episode['locations']);
            DB::table('episodes')->insert($episode);
        }
    }
}
