<?php

namespace App\Console\Commands;

use App\Models\Location;
use DOMNode;
use Goutte\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

use function PHPUnit\Framework\isEmpty;

class ScrapeDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape data of the fandom wikia into the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * Urls to scrape:
         * Characters: https://southpark.fandom.com/wiki/Portal:Characters/Major_Characters
         * Families: https://southpark.fandom.com/wiki/Portal:Characters/Families -> Create characters from this as well
         * Episodes: https://southpark.fandom.com/wiki/Cartman_Gets_an_Anal_Probe/Script -> References characters, so can look them up to link them to an episode
         * 
         * 
         * character_episode: Episode -> cast
         * character_relative: Families -> Character -> Relatives
         * characters: Families -> Character
         * episode_location: Episode -> Story Elements -> Location (find all)
         * episodes: Episodes
         * Locations: Locations -> Major locations
         * 
         */

        $client = new Client();
        // $this->getLocations($client);
        $this->getCharactersAndFamilies($client);
        return 0;
    }

    /**
     * Function responsible for adding Locations in the database.
     */
    public function getLocations(Client $client) {
        $crawler = $client->request('GET', 'https://southpark.fandom.com/wiki/Portal:Locations');
        $crawler->filter('div#gallery-0 div a')->each(function($node) {
            if(!empty($node->text())) {
                $location = new Location([
                    'name' => $node->text(),    //  Is the location name on the node.
                ]);
                $location->save();
            }
        });

        $this->info('Locations created!');
    }

    public function getCharactersAndFamilies(Client $client) {
        $crawler = $client->request('GET', 'https://southpark.fandom.com/wiki/Portal:Characters/Families');
        $maxGalleryNumber = 30;
        for($i = 0; $i <= $maxGalleryNumber; $i++) {
            $crawler->filter('#gallery-' . $i . ' > div')->each(function(Crawler $node) use ($client) {
                $node = $node->filter('div > a')->last();
                $crawler = $client->click($node->selectLink($node->text())->link());
                $name = $crawler->filter('h2[data-source="name"]')->first()->text();
                echo($name);
                die();
                // echo($node->selectLink(''));
                // $crawler = $client->click($node->link());
            });

            //  Doe relatives apart -> in een andere call; sommige bestaan namelijk nog niet.
        }
        return 'test';
    }
}
