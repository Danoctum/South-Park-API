<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    public $baseEndpoint = 'locations/';
    protected $appends =['first_appearance_episode_url'];

    public function episodes() {
        return $this->belongsToMany('App\Models\Episode');
    }

    public function createLocationShowUrlFromId($id) {
        return env('API_URL') . $this->baseEndpoint . $id;
    }

    public function getFirstAppearanceEpisodeUrlAttribute() {
        return route('episodeShow', ['id' => $this->first_appearance_episode_id]);
    }
}
