<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Episode;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $episodes = [];
        $episodeClass = new Episode();
        foreach($this->episodes as $episode) {
            array_push($episodes, $episodeClass->createEpisodeShowUrlFromId($episode->id));
        }

        return array_merge(
            $this->resource->attributesToArray(),
            [
                'episodes' => $episodes,
            ]
        );
    }
}
