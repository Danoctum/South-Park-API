<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Resources\CharacterShowResource;

class CharacterTest extends TestCase
{


    private $characterJsonDataStructure = [
        'id',
        'name',
        'full_name',
        'age',
        'sex',
        'hair_color',
        'occupation',
        'grade',
        'religion',
        'voiced_by',
        'first_appearance_episode_id',
        'created_at',
        'updated_at',
        'url',
        'first_appearance_episode_url',
        'relatives' => [
            '*' => [
                'url',
                'relation',
            ]
        ],
        'episodes'
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCharacterShowResponseStructure()
    {
        $response = $this->get('/api/characters/1');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => $this->characterJsonDataStructure
            ]);
    }

    public function testCharacterIndexResponseStructure() 
    {
        $response = $this->get('/api/characters');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->characterJsonDataStructure
                ]
            ]);
    }

    public function testCharacterNotFound()
    {
        $response = $this->get('/api/characters/cantFindThis');
        $response
            ->assertStatus(404);
    }
}
