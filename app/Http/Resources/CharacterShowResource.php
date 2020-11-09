<?php

namespace App\Http\Resources;

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
            //  TODO: add relation of the pivot table!
            array_push($relatives, $this->createCharacterShowUrlFromId($relative->id));
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

        return array_merge(
            $this->resource->attributesToArray(),
            [
                'relatives' => $relatives,
                'episodes' => $episodes,
            ]
        );
    }
}
