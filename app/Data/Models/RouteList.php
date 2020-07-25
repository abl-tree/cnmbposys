<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

class RouteList extends Model
{
    public function permissions() {
        return $this->hasMany('App\Data\Models\Permission', 'route_id');
    }
}
