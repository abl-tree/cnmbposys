<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use App\Data\Models\BaseModel;

class AccessLevelHierarchy extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'child_id','created_at','updated_at'
    ];

    public function childInfo() {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'child_id');
    }

    public function parentInfo() {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'parent_id');
    }
}
