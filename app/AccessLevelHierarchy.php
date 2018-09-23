<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessLevelHierarchy extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'child_id',
    ];

    public function childInfo() {
        return $this->hasOne('\App\UserInfo', 'id', 'child_id');
    }

    public function parentInfo() {
        return $this->hasOne('\App\UserInfo', 'id', 'parent_id');
    }
}
