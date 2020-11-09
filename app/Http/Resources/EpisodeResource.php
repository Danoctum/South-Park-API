<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Character;
use App\Models\Location;

class EpisodeResource extends JsonResource
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

        $locations = [];
        $locationClass = new Location();
        foreach($this->locations as $location) {
            array_push($locations, $locationClass->createLocationShowUrlFromId($location->id));
        }

        return array_merge(
            $this->resource->attributesToArray(),
            [
                'characters' => $characters,
                'locations' => $locations,
            ]
        );
    }
}
