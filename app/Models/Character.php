<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;
    protected $appends = ['url'];   //  This in combination with getUrlAttribute() becomes a 'virtual field'
    protected $guarded = [];

    public function episodes() {
        return $this->belongsToMany('App\Models\Episode');
    }

    public function relatives() {
        return $this->belongsToMany('App\Models\Character', 'character_relative', 'relative_id', 'character_id')->withPivot('relation');
    }

    public function family() {
        return $this->belongsTo(Family::class, 'families_characters');
    }

    public function getUrlAttribute() {
        return route('characterShow', ['id' => $this->id]);
    }

    public function createCharacterShowUrlFromId($id) {
        return route('characterShow', ['id' => $id]);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where([
            ['name', 'LIKE', '%'.$keyword.'%'],
        ]);
    }
}
