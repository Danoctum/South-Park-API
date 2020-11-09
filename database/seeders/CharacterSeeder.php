<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $characters = [
            [
                'name' => 'Stan',
                'full_name' => 'Stanley Marsh',
                'sex' => 'male',
                'age' => '10',
                'hair_color' => 'Black',
                'occupation' => 'Student',
                'grade' => '4th grade',
                'religion' => 'Roman Catholic',
                'voiced_by' => 'Trey Parker',
                'first_appearance_episode_id' => 1,
            ],
            [
                'name' => 'Kyle',
                'full_name' => 'Kyle Brovloski',
                'sex' => 'male',
                'age' => '9',
                'hair_color' => 'Red',
                'occupation' => 'Student',
                'grade' => '4th grade',
                'religion' => 'Judasim',
                'voiced_by' => 'Matt Stone',
                'first_appearance_episode_id' => 1,
            ],
            [
                'name' => 'Eric',
                'full_name' => 'Eric Theodore Cartman',
                'sex' => 'male',
                'age' => '10',
                'hair_color' => 'Brown',
                'occupation' => 'Student',
                'grade' => '4th grade',
                'religion' => 'Roman Catholic',
                'voiced_by' => 'Trey Parker',
                'first_appearance_episode_id' => 1,
            ],
            [
                'name' => 'Kenny',
                'full_name' => 'Kenneth McCormick',
                'sex' => 'male',
                'age' => '9',
                'hair_color' => 'blond',
                'occupation' => 'Student',
                'grade' => '4th grade',
                'religion' => 'Roman Catholic',
                'voiced_by' => 'Matt Stone, Eric Stough',
                'first_appearance_episode_id' => 1,
            ],
            [
                'name' => 'Randy Marsh',
                'full_name' => 'Randy Marsh',
                'sex' => 'male',
                'age' => '45',
                'hair_color' => 'black',
                'occupation' => 'Geologist',
                'religion' => 'Roman Catholic',
                'voiced_by' => 'Trey Parker',
                'first_appearance_episode_id' => 2,
            ],
        ];


        foreach($characters as $character) {
            DB::table('characters')->insert($character);
        }
    }
}
