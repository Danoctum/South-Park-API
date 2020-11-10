<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = Storage::get('characters.json');
        $charactersArray = json_decode($json, true);


        foreach($charactersArray['characters'] as $character) {
            unset($character['relatives']);
            DB::table('characters')->insert($character);
        }

        foreach($charactersArray['characters'] as $character) {
            foreach($character['relatives'] as $relation) {
                DB::table('character_relative')->insert([
                    'character_id' => $character['id'],
                    'relative_id' => $relation['id'],
                    'relation' => $relation['relation']
                ]);
            }
        }
    }
}
