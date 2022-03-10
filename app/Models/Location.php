<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function episodes() {
        return $this->belongsToMany('App\Models\Episode');
    }

    public function createLocationShowUrlFromId($id) {
        return route('locationShow', ['id' => $id]);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where([
            ['name', 'LIKE', '%'.$keyword.'%']
        ]);
    }
}
