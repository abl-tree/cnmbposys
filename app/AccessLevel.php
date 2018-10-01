<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
    public function getAllParentEmployee(){
        $dbraw = 'max(case when users.access_id = 1 then user_infos.id end) as adminid, max(case when users.access_id = 1 then CONCAT_WS(" ",user_infos.firstname,user_infos.lastname) end) as admin';
        $dbraw.= ',max(case when users.access_id = 2 then user_infos.id end) as hrmid, max(case when users.access_id = 2 then CONCAT_WS(" ",user_infos.firstname,user_infos.lastname) end) as hrm';
        $dbraw.= ',max(case when users.access_id = 3 then user_infos.id end) as omid, max(case when users.access_id = 3 then CONCAT_WS(" ",user_infos.firstname,user_infos.lastname) end) as om';
        $dbraw.= ',max(case when users.access_id = 9 then user_infos.id end) as tlid, max(case when users.access_id = 9 then CONCAT_WS(" ",user_infos.firstname,user_infos.lastname) end) as tl';
        $dbraw.= ',max(case when users.access_id = 5 then user_infos.id end) as rtamid, max(case when users.access_id = 5 then CONCAT_WS(" ",user_infos.firstname,user_infos.lastname) end) as rtam';
        $dbraw.= ',max(case when users.access_id = 6 then user_infos.id end) as tqmid, max(case when users.access_id = 6 then CONCAT_WS(" ",user_infos.firstname,user_infos.lastname) end) as tqm';
        $parent = DB::table('user_infos')
        ->join('users','users.uid','=','user_infos.id')
        ->join('access_levels','access_levels.id','=','users.access_id')
        ->select(DB::raw($dbraw))
        ->orderBy('user_infos.id');





        return $parent;
    }
}
