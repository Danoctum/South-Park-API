<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EpisodeSeeder::class,
            CharacterSeeder::class,
            LocationSeeder::class,
            EpisodeLocationSeeder::class,
            CharacterEpisodeSeeder::class,
        ]);
    }
}
