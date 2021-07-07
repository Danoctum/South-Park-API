<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Models\Episode;
use App\Models\Location;
use Carbon\Carbon;
use Database\Seeders\CharacterSeeder;
use DOMNode;
use Goutte\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        // $this->getCharactersAndFamilies($client);
        $this->getRelatives($client);
        // $this->getEpisodes($client);
        return 0;
    }

    /**
     * Function responsible for adding Locations in the database.
     */
    public function getLocations(Client $client) {
        $crawler = $client->request('GET', 'https://southpark.fandom.com/wiki/Portal:Locations');
        $crawler->filter('div.mw-parser-output div.wikia-gallery a:not(.image)')->each(function($node) {
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
        $crawler->filter('div.mw-parser-output div.wikia-gallery > div')->each(function(Crawler $node) use ($client) {
            $node = $node->filter('div > a')->last();
            $characterCrawler = $client->click($node->selectLink($node->text())->link());
            $name = $characterCrawler->filter('h1.page-header__title')->first()->text();

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

        $this->info('Characters created!');
    }



    public function getRelatives(Client $client) {
        $crawler = $client->request('GET', 'https://southpark.fandom.com/wiki/Portal:Characters/Families');
        $crawler->filter('div.mw-parser-output div.wikia-gallery div')->each(function(Crawler $node) use ($client) {
            $node = $node->filter('div > a:not(.image)');
            $characterCrawler = $client->click($node->selectLink($node->text())->link());
            //  Get character by name.
            $characterCrawler->filter('h2:contains("relatives")')->each(function(Crawler $node) {
                $test = $node->first();
                $test2 = null;
                //  Set relative to singular; care for double ones like on https://southpark.fandom.com/wiki/Eric_Cartman.
                    
            });
        });
    }

    public function getEpisodes(Client $client) {
        $crawler = $client->request('GET', 'https://southpark.fandom.com/wiki/Season_One');
        $season = 1;
        $nextPageButtonCount = $crawler->filter('tbody tr td:last-child a')->count();
        do {
            //  Get the episode
            $crawler->filter('table tbody tr[style="text-align:center;"]')->each(function(Crawler $crawler, $iteration) use ($season, $client) {
                //  Filtering for each row with episosde details.
                $episodeDetails = $crawler->filter('td:not([rowspan="2"])');
                $name = $episodeDetails->getNode(0)->textContent;
                $name = explode('"', $name)[1];
                if($name === 'TBA') {   //  At the end there are some episodes that are TBA placeholders.
                    return;
                }
                $airDate = $episodeDetails->getNode(1)->textContent;
                $date = new Carbon($airDate);
                $formattedAirDate = $date->toDateString();
                $episode = new Episode([
                    'name' => $name,
                    'season' => $season,
                    'episode' => $iteration + 1,
                    'air_date' => $formattedAirDate,
                ]);
                $episode->save();

                //  Get the characters that were in this episode; assumes that the characters are already in the db.
                $episodePageCrawler = $client->click($crawler->filter('td[style="font-size:125%"]')->selectLink($name)->link());
                $episodeExtrasCrawler = $client->click($episodePageCrawler->filter('div.mw-parser-output a')->selectLink('Extras')->link());
                $episodeExtrasCrawler->filter('div.mw-parser-output div.wikia-gallery')->each(function(Crawler $crawler) use ($episode) {
                    $crawler->filter('div > a:not(.image)')->each(function(Crawler $crawler) use ($episode) {
                        if($characterInEpisode = DB::table('characters')->where('name', 'like',  '%' . $crawler->text() . '%')->first()) {
                            $episode->characters()->syncWithoutDetaching($characterInEpisode->id);
                        }

                        if($locationInEspisode = DB::table('locations')->where('name', $crawler->text())->first()) {
                            $episode->locations()->syncWithoutDetaching($locationInEspisode->id);
                        }
                    });
                });
            });


            //  Go to next page.
            $crawler = $client->click($crawler->filter('tbody tr td:last-child a')->first()->selectLink('Season')->link());
            $season++;
            $nextPageButtonCount = $crawler->filter('tbody tr td:last-child a')->count();
        } while ($nextPageButtonCount > 0);
        $this->info('Episodes created and characters are linked to the episodes!');

    }


}
