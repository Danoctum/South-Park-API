<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    public $baseEndpoint = 'episode/';
    protected $guarded = [];

    public function locations() {
        return $this->belongsToMany('App\Models\Location');
    }

    public function characters() {
        return $this->belongsToMany('App\Models\Character');
    }

    public function createEpisodeShowUrlFromId($id) {
        return route('episodeShow', ['id' => $id]);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where([
            ['name', 'LIKE', '%'.$keyword.'%']
        ]);
    }
}
