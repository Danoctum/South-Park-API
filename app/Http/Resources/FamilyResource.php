<?php

namespace App\Http\Resources;

use App\Models\Character;
use App\Models\Episode;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $characters = [];
        $characterClass = new Character();
        foreach($this->characters as $character) {
            array_push($characters, $characterClass->createCharacterShowUrlFromId($character->id));
        }

        return array_merge(
            $this->resource->attributesToArray(),
            [
                'characters' => $characters,
            ]
        );
    }
}
