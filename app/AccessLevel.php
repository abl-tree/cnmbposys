<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AccessLevel extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name','parent',
    ];
    
    /**
     * Set the access level's name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function users() {
        return $this->hasMany('\App\User', 'access_id', 'id');
    }

    public function children() {
        return $this->hasMany('\App\AccessLevelHierarchy', 'parent_id', 'id');
    }

    public function parents() {
        return $this->hasMany('\App\AccessLevelHierarchy', 'child_id', 'id');
    }

    public function getParentLevel($position){
        $parent = AccessLevel::where('id','=',$position)->select('parent')->first();
        return $parent->parent;
    }
    
    public function getIdByPositionName($name){
        return AccessLevel::where('name',$name)->pluck('id')->first();
    }

    public function getParentCodeByPositionName($position){
        $tmp = AccessLevel::where('name',$position)->select('parent')->first();
        $parent_id = $tmp->parent;
        $tmp = AccessLevel::where('id',$parent_id)->select('code')->first();
        return $tmp->code;
    }
}
