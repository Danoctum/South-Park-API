<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Character;

class CharacterRelativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $relativesPerCharacter = (object) [
            //  Check if there is a possibility to change 'Father' to 'Son' when getting inverse.
            1 => [
                5 => [
                    'relation' => 'Father',
                ]
            ],
            5 => [
                1 => [
                    'relation' => 'Son',
                ]
            ],
        ];


        foreach($relativesPerCharacter as $characterId=>$relativeIdsWithRelation) {
            $character = Character::find($characterId);
            foreach($relativeIdsWithRelation as $relativeId=>$relationInfo) {
                // dd($relativeId, $relationInfo);
                $character->relatives()->attach($relativeId, $relationInfo);
            }
        }
    }
}
