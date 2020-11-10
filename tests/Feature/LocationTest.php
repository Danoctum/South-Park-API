<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationTest extends TestCase
{
    private $locationJsonDataStructure = [
        'id',
        'name',
        'address',
        'type',
        'town',
        'first_appearance_episode_id',
        'created_at',
        'updated_at',
        'episodes',
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLocationShowResponseStructure()
    {
        $response = $this->get('/api/locations/1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->locationJsonDataStructure
            ]);
    }

    public function testLocationIndexResponseStructure()
    {
        $response = $this->get('/api/locations');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->locationJsonDataStructure
                ]
            ]);
    }

    public function testLocationNotFound()
    {
        $response = $this->get('/api/locations/cantFindThis');
        $response
            ->assertStatus(404);
    }
}
