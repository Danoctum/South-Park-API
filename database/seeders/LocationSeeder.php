<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                'name' => 'Bus Stop',
                'address' => null,
                'type' => 'Public',
                'town' => 'South Park',
                'first_appearance_episode_id' => 1,
            ],
            [
                'name' => 'South Park Elementary',
                'address' => null,
                'type' => 'School',
                'town' => 'South Park',
                'first_appearance_episode_id' => 1,
            ],
            [
                'name' => 'Cartman Residence',
                'address' => '28201 E. Bonanza St.',
                'type' => 'Residential',
                'town' => 'South Park',
                'first_appearance_episode_id' => 1,
            ],
            [
                'name' => "Stark's Pond",
                'address' => null,
                'type' => 'Boating Lake',
                'town' => 'South Park',
                'first_appearance_episode_id' => 1,
            ],
            [
                'name' => "Book Depository",
                'address' => null,
                'type' => 'Warehouse',
                'town' => null,
                'first_appearance_episode_id' => 2,
            ],
            [
                'name' => "City Hall",
                'address' => null,
                'type' => 'Public',
                'town' => 'South Park',
                'first_appearance_episode_id' => 2,
            ],
            [
                'name' => "Garrison Residence",
                'address' => null,
                'type' => 'Residential',
                'town' => 'South Park',
                'first_appearance_episode_id' => 2,
            ],
            [
                'name' => "Jimbo's guns",
                'address' => null,
                'type' => 'Gun Store',
                'town' => 'South Park',
                'first_appearance_episode_id' => 2,
            ],
            [
                'name' => "Town Square",
                'address' => null,
                'type' => 'Public',
                'town' => 'South Park',
                'first_appearance_episode_id' => 2,
            ],
            [
                'name' => "Denver",
                'address' => null,
                'type' => 'City',
                'town' => 'Denver',
                'first_appearance_episode_id' => 3,
            ],
            [
                'name' => "Sotuh Park Center for Seismic Activity",
                'address' => null,
                'type' => 'Public',
                'town' => 'South Park',
                'first_appearance_episode_id' => 3,
            ],
            [
                'name' => "Volcano",
                'address' => null,
                'type' => 'Public',
                'town' => 'South Park',
                'first_appearance_episode_id' => 3,
            ],
        ];

        foreach($locations as $location) {
            DB::table('locations')->insert($location);
        }
    }
}
