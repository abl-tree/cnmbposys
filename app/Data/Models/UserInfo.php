<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Data\Models\UserBenefit;
use App\User;
use App\Data\Models\HierarchyLog;
use App\Data\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class UserInfo extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $primaryKey = 'id';
    protected $table = 'user_infos';
    protected $fillable = [
        'firstname','middlename', 'lastname','suffix',
        'birthdate', 'gender', 'contact_number',
        'address', 'image', 'salary_rate','image_url',
        'status','type','hired_date', 'separation_date', 'excel_hash',
        'p_email','created_at','updated_at'
    ];

    protected $searchable = [
        'firstname','middlename', 'lastname', 'suffix',
        'birthdate', 'gender', 'contact_number',
        'address', 'image', 'salary_rate','image_url',
        'status', 'hired_date', 'separation_date', 'excel_hash',
        'p_email','created_at','updated_at','full_name','access.id'
    ];

    protected $appends = [
        'full_name','count','image', 'current_head', 
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'image','image_ext'
    ];

     //Mutator
    public function setFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = strtolower(trim($value));
    }

    public function setMiddlenameAttribute($value)
    {
        $this->attributes['middlename'] = strtolower(trim($value));
    }

    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = strtolower(trim($value));
    }

    public function setSuffixAttribute($value)
    {
        $this->attributes['suffix'] = strtolower(trim($value));
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtolower($value);
    }

    public function setExcelHashAttribute($value)
    {
        $this->attributes['excel_hash'] = str_replace(' ', '', strtolower($value));
    }

    //Accessors
    public function getFirstnameAttribute($value)
    {
        return ucwords($value);
    }

    public function getMiddlenameAttribute($value)
    {
        return ucwords($value);
    }

    public function getLastnameAttribute($value)
    {
        return ucwords($value);
    }

    public function getSuffixAttribute($value)
    {
        return ucwords($value);
    }

    public function getAddressAttribute($value)
    {
        return ucwords($value);
    }


    public function getFullNameAttribute(){
        $name = null;
        $name = ucwords($this->firstname) . ' ' .ucwords($this->middlename) . ' ' . ucwords($this->lastname). ' ' . ucwords($this->suffix);
        return $name;
    }

    public function getCurrentHeadAttribute(){
        $result = HierarchyLog::with(["parent_details"])->where("child_id", $this->id)->whereNull('end_date')->first();
        return $result;
    }


    //Relationships


    public function child_logs(){
        return $this->hasMany('\App\Data\Models\HierarchyLog', 'parent_id', 'id');
    }

    public function parent_logs(){
        return $this->hasMany('\App\Data\Models\HierarchyLog', 'child_id', 'id');
    }


    public function user() {
        return $this->hasOne('\App\User', 'uid', 'id');
    }

    public function benefits() {
        return $this->hasMany('\App\Data\Models\UserBenefit', 'user_info_id', 'id');
    }
     public function reports() {
        return $this->hasMany('\App\Data\Models\UserReport' ,'user_reports_id', 'id')->with("SanctionType","SanctionLevel","filedby","agentResponse");
    }
      public function sanctiontype() {
        return $this->belongsTo('\App\Data\Models\SanctionType', 'saction_type_id', 'type_number');
    }
    public function accesslevel(){
       return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'access_id');
    }
    public function accesslevelhierarchy(){
        return $this->hasOne('\App\Data\Models\AccessLevelHierarchy', 'child_id', 'id');
     }
    public function access(){
        return $this->hasOne('\App\Data\Models\AccessLevel', 'id', 'access_id');
     }

    public function schedule()
    {
        return $this->hasMany('\App\Data\Models\AgentSchedule', 'user_id', 'id');
    }

    public function getAllEmployee(){
        $query = DB::table('user_infos')
        ->join('users','users.uid','=','user_infos.id')
        ->join('user_benefits','user_benefits.user_info_id','=','user_infos.id')
        ->join('access_levels','access_levels.id','=','users.access_id')
        ->join('access_level_hierarchies','access_level_hierarchies.child_id','=','user_infos.id')
        ->select('user_infos.image','users.company_id','user_infos.firstname','user_infos.middlename','user_infos.lastname','user_infos.status','user_infos.gender','user_infos.birthdate','user_infos.address','user_infos.p_email','users.email','user_infos.contact_number',DB::raw('max(case when user_benefits.benefit_id = 1 then id_number end) as col1'),DB::raw('max(case when user_benefits.benefit_id = 2 then id_number end) as col2'),DB::raw('max(case when user_benefits.benefit_id = 3 then id_number end) as col3'),DB::raw('max(case when user_benefits.benefit_id = 4 then id_number end) as col4'),'access_levels.name','user_infos.hired_date','user_infos.separation_date','user_infos.status_reason','user_infos.status')
        ->groupBy('user_infos.id')
        ->where('user_infos.id','!=', 3)
        ->orderBy('user_infos.id','asc')->get();
        return $query;
    }
    public function getCountAttribute(){
        return $this->hasMany('\App\Data\Models\UserReport' ,'user_reports_id', 'id')->count();
    }


}