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
        'age',
        'sex',
        'hair_color',
        'occupation',
        'grade',
        'religion',
        'voiced_by',
        'created_at',
        'updated_at',
        'url',
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

    public function testCharacterSearchResponseStructure() 
    {
        $response = $this->get('/api/characters?search=eric');
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
