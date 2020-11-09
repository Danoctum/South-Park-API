<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    public $baseEndpoint = 'episode/';

    public function locations() {
        return $this->belongsToMany('App\Models\Location');
    }

    public function characters() {
        return $this->belongsToMany('App\Models\Character');
    }

    public function createEpisodeShowUrlFromId($id) {
        return env('API_URL') . $this->baseEndpoint . $id;
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where([
            ['name', 'LIKE', '%'.$keyword.'%']
        ]);
    }
}
