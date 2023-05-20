<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EpisodeTest extends TestCase
{

    private $episodeJsonDataStructure = [
        'id',
        'name',
        'season',
        'episode',
        'air_date',
        'created_at',
        'updated_at',
        'characters',
        'locations',
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testEpisodeShowResponseStructure()
    {
        $response = $this->get('/api/episodes/1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->episodeJsonDataStructure
            ]);
    }

    public function testEpisodeIndexResponseStructure()
    {
        $response = $this->get('/api/episodes');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->episodeJsonDataStructure
                ]
            ]);
    }

    public function testEpisodeSearchResponseStructure()
    {
        $response = $this->get('/api/episodes?search=Probe');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->episodeJsonDataStructure
                ]
            ]);
    }

    public function testEpisodeNotFound()
    {
        $response = $this->get('/api/episodes/cantFindThis');
        $response
            ->assertStatus(404);
    }
}
