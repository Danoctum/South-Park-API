<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\DB;

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
            array_push($relatives, $this->createCharacterShowUrlFromId($relative->id));
        }

        return array_merge(
            $this->resource->attributesToArray(),
            [
                'relatives' => $relatives,
            ]
        );
        return parent::toArray($request);
    }
}
