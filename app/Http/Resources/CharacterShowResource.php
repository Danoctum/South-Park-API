<?php

namespace App\Http\Resources;

use App\Models\Family;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Episode;

class CharacterShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $relatives = [];
        foreach($this->relatives as $relative) {
            array_push($relatives, [
                'url' => $this->createCharacterShowUrlFromId($relative->id),
                'relation' => $relative->pivot->relation
                //  Relation data is added separately, since a 'father' can have multiple inverses, i.e. 'son' or 'daughter'
            ]);
        }

        $episodes = [];
        //  Q: Is it better to use a static function without class initializiation, or non-static functions with class initialization?
        //  i.e. Episode::createEpisodeShowUrlFromId($episode->id);
        //  And then have the createEpisodeShowUrlFromId static.
        //  Can't be done exactly the same as with the relatives since that class is injected into $this.
        $episodeClass = new Episode();
        foreach($this->episodes as $episode) {
            array_push($episodes, $episodeClass->createEpisodeShowUrlFromId($episode->id));
        }

        $familyClass = new Family();
        $familyUrl = $familyClass->createFamilyUrlFromId($this->family_id);
        unset($this->family_id);

        return array_merge(
            $this->resource->attributesToArray(),
            [
                'family' => $familyUrl,
                'relatives' => $relatives,
                'episodes' => $episodes,
            ]
        );
    }
}
