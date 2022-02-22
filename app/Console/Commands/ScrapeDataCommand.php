<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Models\Episode;
use App\Models\Location;
use Carbon\Carbon;
use Database\Seeders\CharacterSeeder;
use DOMNode;
use Exception;
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

    private $locationUrl = 'https://southpark.fandom.com/wiki/Portal:Locations';
    private $familiesUrl = 'https://southpark.fandom.com/wiki/Portal:Characters/Families';
    private $episodesUrl = 'https://southpark.fandom.com/wiki/Season_One';


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
     * All of the save() queries don't check if the character exists.
     * This is due to having to delete all of the information before running the command, since otherwise data would be out of sync (some would be updated, some wouldn't be).
     *
     * @return int
     */
    public function handle()
    {
        $this->warn('The command will give lots of output so you can see everything that is added from the command line.');
        try {
            $client = new Client();
//            $this->getLocations($client);
//            $this->getCharactersAndFamilies($client);
//            $this->getRelatives($client);
            $this->getEpisodes($client);
        } catch (Exception $error) {
            $this->warn($error);
            return 1;
        };
        return 0;
    }

    /**
     * Function responsible for adding Locations in the database.
     */
    public function getLocations(Client $client)
    {
        $this->info('Attempting to retrieve locations.');
        $crawler = $client->request('GET', $this->locationUrl);
        $crawler->filter('div.mw-parser-output div.wikia-gallery a:not(.image)')->each(function ($node) {
            if (!empty($node->text())) {
                $location = new Location([
                    'name' => $node->text(),    //  Is the location name on the node.
                ]);
                $location->save();
                $this->comment('Added location: ' . $location->name);
            }
        });

        $this->info('Locations created!');
    }

    /**
     * Function to get the characters.
     * Loops through every gallery on the families URL.
     * Then visits every character in the gallery and gets their details.
     * TODO: get families and add them.
     */
    public function getCharactersAndFamilies(Client $client)
    {
        $this->info('Attempting to retrieve characters and families.');
        $crawler = $client->request('GET', $this->familiesUrl);
        $crawler->filter('div.mw-parser-output div.wikia-gallery > div')->each(function (Crawler $node) use ($client) {
            $node = $node->filter('div > a')->last();
            $characterCrawler = $client->click($node->selectLink($node->text())->link());
            $name = $this->getCharacterProperty($characterCrawler, 'h1.page-header__title');
            if (in_array($name, $this->blackListedCharacters)) {
                return; //  Some characters aren't really characters, or are a collection of. Blacklisting those.
            }

            $age = $this->getCharacterProperty($characterCrawler, 'div[data-source="age"] div');
            if ($age !== null && Str::contains($age, '-')) { //  Some characters have a dash because age is unclear (4-5).
                $age = Str::before($age, '-');  //  Gets the younger age.
            }

            if ($age !== null && Str::contains($age, '[')) { //  Have to clean references like [1].
                $age = Str::before($age, '[');
            }

            if ($age !== null) {
                $age = filter_var($age, FILTER_SANITIZE_NUMBER_INT);    //  Remove characters from age like (deceased).
            }

            $sex = $this->getListsedCharacterProperty($characterCrawler, 'div[data-source="gender"] div', '<small>');
            $hairColor = $this->getCharacterProperty($characterCrawler, 'div[data-source="hair"] div');
            $occupation = $this->getListsedCharacterProperty($characterCrawler, 'div[data-source="job"] div', '<br>');
            $occupation = strip_tags($occupation);
            if ($occupation === '') {
                $occupation = null; //  Sometimes occupation is just a tag and we want to show NULL instead of ''.
            }

            $grade = $this->getCharacterProperty($characterCrawler, 'div[data-source="grade"] div');
            $religion = $this->getListsedCharacterProperty($characterCrawler, 'div[data-source="religion"] div', '<br>');
            if ($religion !== null && str_contains($religion, ';')) {
                $religion = substr($religion, 0, strpos($religion, ';'));   //  Some religions are divided by ; instead of <br>. Get the first religion listed.
            }

            $religion = strip_tags($religion);
            if ($religion === '') {
                $religion = null;
            }

            $voicedBy = $this->getCharacterProperty($characterCrawler, 'div[data-source="voice"] div');
            if ($voicedBy !== null && str_contains($voicedBy, '[')) {
                $voicedBy = null;   //  Sometimes the voice actor is a non-link with a reference. Make those null due to bad data.
            }

            if (in_array($voicedBy, $this->blackListedVoicedBy)) {
                $voicedBy = null;   //  Some pages get retrieved in an odd way due to page inconsistency, remove those outliers.
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
            $this->comment('Added character: ' . $character->name);
        });

        $this->info('Characters created!');
    }

    /**
     * Checks if a property is found and returns the text of that property.
     * Selector should point to an element with a single line of text.
     */
    private function getCharacterProperty($crawler, $selector)
    {
        return $crawler->filter($selector)->first()->count()
            ? $crawler->filter($selector)->first()->text()
            : null;
    }

    /**
     * Sometimes selectors return a list of items.
     * In that case get the first item (deemed as most important).
     * Other cleaning like ; and [] are done outside of this function.
     */
    private function getListsedCharacterProperty($crawler, $selector, $explodeElement)
    {
        return $crawler->filter($selector)->first()->count()
            ? explode($explodeElement, $crawler->filter($selector)->first()->html(), 2)[0]
            : null;
    }



    /**
     * Loops trough the characters one more time to get their relatives.
     * This is due to all characaters not being in the database when characters are being retrieved.
     * Saves the relation from character to relation;
     * i.e. if Kyle is the character and Gerald is the relative, the relation would be father.
     */
    public function getRelatives(Client $client)
    {
        $this->info('Attempting to retrieve character relatives. This part of the command may take long time.');
        $crawler = $client->request('GET', $this->familiesUrl);
        $crawler->filter('div.mw-parser-output div.wikia-gallery > div')->each(function (Crawler $node) use ($client) {
            $characaterURLNode = $node->filter('div a:not(.image)');
            $characterCrawler = $client->click($characaterURLNode->selectLink($characaterURLNode->text())->link());
            $name = $characterCrawler->filter('h1.page-header__title')->first()->text();
            if (in_array($name, $this->blackListedCharacters)) {
                return;
            }

            $character = Character::where('name', $name)->first();
            if (!$character) {
                return;
            }

            $this->info('Adding relatives for: ' . $character->name);
            if ($characterCrawler->filter('h2:contains("Relatives")')->count() === 0) {
                return;
            }

            $characterCrawler->filter('h2:contains("Relatives")')->siblings()->each(function (Crawler $node) use ($character) {
                $relation = $node->filter('h3')->text();
                $node->filter('a')->each(function (Crawler $node) use ($character, $relation) {
                    $relative = $node->text();
                    if ($relative = Character::where('name', $relative)->first()) {
                        $relative->relatives()->syncWithoutDetaching([$character->id => ['relation' => Str::singular($relation)]]);
                    }
                });
            });
        });
    }

    /**
     * Gets the episodes and searches every character and location in that episode.
     * Locations and characters have to be added first for this to work.
     */
    public function getEpisodes(Client $client)
    {
        $this->info('Attempting to retrieve episodes and link existing characters and locations to them.');
        $crawler = $client->request('GET', $this->episodesUrl);
        $season = 1;
        $nextPageButtonCount = $crawler->filter('tbody tr td:last-child a')->count();
        do {
            //  Get the episode
            $crawler->filter('table tbody tr[style="text-align:center;"]')->each(function (Crawler $crawler, $iteration) use ($season, $client) {
                //  Filtering for each row with episosde details.
                $episodeDetails = $crawler->filter('td');
                $episodeDescription = $crawler->parents()->first()->filter('p')->getNode($iteration)->textContent;
                $episodeImageUrl = $episodeDetails->filter('.image')->getNode(0)->attributes[0]->value;

                $name = $episodeDetails->getNode(1)->textContent;
                $name = explode('"', $name)[1];
                if ($name === 'TBA') {   //  At the end there are some episodes that are TBA placeholders.
                    return;
                }
                $airDate = $episodeDetails->getNode(2)->textContent;
                $date = new Carbon($airDate);
                $formattedAirDate = $date->toDateString();
                $episode = new Episode([
                    'name' => $name,
                    'season' => $season,
                    'episode' => $iteration + 1,
                    'air_date' => $formattedAirDate,
                    'description' => $episodeDescription,
                    'thumbnail_url' => $episodeImageUrl,
                ]);
                $episode->save();
                $this->comment('Added episode: ' . $name);
                //  Get the characters that were in this episode; assumes that the characters are already in the db.
                $episodePageCrawler = $client->click($crawler->filter('td[style="font-size:125%"]')->selectLink($name)->link());
                $extrasLinkSelector = $episodePageCrawler->filter('div.mw-parser-output a')->selectLink('Extras');
                if ($extrasLinkSelector->count() === 0) {   //  Check if Extras tab exists
                    return;
                }

                $episodeExtrasCrawler = $client->click($extrasLinkSelector->link());
                $episodeExtrasCrawler->filter('div.mw-parser-output div.wikia-gallery')->each(function (Crawler $crawler) use ($episode) {
                    $crawler->filter('div > a:not(.image)')->each(function (Crawler $crawler) use ($episode) {
                        if ($characterInEpisode = Character::where('name', 'like',  '%' . $crawler->text() . '%')->first()) {
                            $episode->characters()->syncWithoutDetaching($characterInEpisode->id);
                        }

                        if ($locationInEspisode = Location::where('name', $crawler->text())->first()) {
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
        $this->info('Episodes created! Characters and locations are linked to the episodes!');
    }
}
