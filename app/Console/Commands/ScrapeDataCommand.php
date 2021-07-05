<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Models\Location;
use DOMNode;
use Goutte\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isEmpty;

class ScrapeDataCommand extends Command
{
    private $blackListedCharacters = [
        "Michael's parents",
    ];

    private $blackListedVoicedBy = [
        "Cartman's Mom is a Dirty Slut",
    ];

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
                $characterCrawler = $client->click($node->selectLink($node->text())->link());
                $name = $characterCrawler->filter('h2[data-source="name"]')->first()->text();

                if(in_array($name, $this->blackListedCharacters)) {
                    return;
                }

                $age = $characterCrawler->filter('div[data-source="age"] div')->first()->count()
                    ? $characterCrawler->filter('div[data-source="age"] div')->first()->text()
                    : null;
                if($age !== null && Str::contains($age, '-')) { //  Some characters have a dash because age is unclear (4-5).
                    $age = Str::before($age, '-');
                }
                if($age !== null && Str::contains($age, '[')) { //  Have to clean asterixes like [1].
                    $age = Str::before($age, '[');
                }
                if($age !== null) {
                    $age = filter_var($age, FILTER_SANITIZE_NUMBER_INT);    //  Remove characters from age like (deceased).
                }
                $sex = $characterCrawler->filter('div[data-source="gender"] div')->first()->count()
                    ? explode("<small>", $characterCrawler->filter('div[data-source="gender"] div')->first()->html(), 2)[0]
                    : null;
                $hairColor = $characterCrawler->filter('div[data-source="hair"] div')->first()->count()
                    ? $characterCrawler->filter('div[data-source="hair"] div')->first()->text()
                    : null;
                $occupation = $characterCrawler->filter('div[data-source="job"] div')->first()->count()
                    ? explode("<br>", $characterCrawler->filter('div[data-source="job"] div')->first()->html(), 2)[0]
                    : null;
                $occupation = strip_tags($occupation);
                if($occupation === '') {
                    $occupation = null;
                }
                $grade = $characterCrawler->filter('div[data-source="grade"] div')->first()->count()
                    ? $characterCrawler->filter('div[data-source="grade"] div')->first()->text()
                    : null;
                $religion = $characterCrawler->filter('div[data-source="religion"] div')->first()->count()
                    ? explode("<br>", $characterCrawler->filter('div[data-source="religion"] div')->first()->html(), 2)[0]
                    : null;
                if($religion !== null && str_contains($religion, ';')) {
                    $religion = substr($religion, 0 , strpos($religion, ';'));
                }
                $religion = strip_tags($religion);
                if($religion === '') {
                    $religion = null;
                }
                $voicedBy = $characterCrawler->filter('div[data-source="voice"] div')->first()->count()
                    ? $characterCrawler->filter('div[data-source="voice"] div a')->first()->text()
                    : null;
                if($voicedBy !== null && str_contains($voicedBy, '[')) {
                    $voicedBy = null;
                }
                if(in_array($voicedBy, $this->blackListedVoicedBy)) {
                    $voicedBy = null;
                }

                $character = new Character([
                    'name' => $name,
                    'age' => $age,
                    'sex' => $sex,
                    'hair_color' => $hairColor,
                    'occupation' => $occupation,
                    'grade' => $grade,
                    'religion' => $religion,
                    'voiced_by' => $voicedBy
                ]);
                $character->save();
            });

            //  Doe relatives apart -> in een andere call; sommige bestaan namelijk nog niet.
        }
        return 'test';
    }
}
