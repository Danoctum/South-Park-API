<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function characters() {
        return $this->hasMany('App\Models\Character');
    }

    public function createFamilyUrlFromId($id) {
        return route('familyShow', ['id' => $id]);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where([
            ['name', 'LIKE', '%'.$keyword.'%']
        ]);
    }
}
