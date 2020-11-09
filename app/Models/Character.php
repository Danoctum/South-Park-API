<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;
    public $baseEndpoint = 'characters/';
    protected $appends = ['url', 'first_appearance_episode_url'];   //  This in combination with getUrlAttribute() becomes a 'virtual field'

    public function episodes() {
        return $this->belongsToMany('App\Models\Episode');
    }

    public function relatives() {
        return $this->belongsToMany('App\Models\Character', 'character_relative', 'relative_id', 'character_id')->withPivot('relation');
    }
    
    public function getUrlAttribute() {
        return route('characterShow', ['id' => $this->id]);
    }

    public function getFirstAppearanceEpisodeUrlAttribute() {
        return route('episodeShow', ['id' => $this->first_appearance_episode_id]);
    }

    public function createCharacterShowUrlFromId($id) {
        return env('API_URL') . $this->baseEndpoint . $id;
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where([
            ['name', 'LIKE', '%'.$keyword.'%'],
            ['full_name', 'LIKE', '%'.$keyword.'%']
        ]);
    }
}
