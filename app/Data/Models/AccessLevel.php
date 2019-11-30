<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Data\Models\BaseModel;

class AccessLevel extends BaseModel
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name','parent','flag'
    ];
    
    protected $searchable = [
       'name','code'
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
        return $this->hasMany('\App\Data\Models\AccessLevelHierarchy', 'parent_id', 'id');
    }

    public function parents() {
        return $this->hasMany('\App\Data\Models\AccessLevelHierarchy', 'child_id', 'id');
    }
    
    public function parent() {
        return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'parent');
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