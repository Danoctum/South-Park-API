<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    public function locations() {
        return $this->belongsToMany('App\Models\Location');
    }

    public function characters() {
        return $this->belongsToMany('App\Models\Character');
    }
}
