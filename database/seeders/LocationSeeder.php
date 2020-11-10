<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = Storage::get('locations.json');
        $locationsArray = json_decode($json, true);


        foreach($locationsArray['locations'] as $location) {
            DB::table('locations')->insert($location);
        }
    }
}
