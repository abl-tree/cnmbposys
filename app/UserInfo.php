<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\UserBenefit;

class UserInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'middlename', 'lastname', 'birthdate', 'gender', 'contact_number', 'address', 'image', 'salary_rate','image_ext', 'status', 'hired_date', 'separation_date', 'excel_hash'
    ];

     //Mutator
    public function setFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = strtolower($value);
    }

    public function setMiddlenameAttribute($value)
    {
        $this->attributes['middlename'] = strtolower($value);
    }

    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = strtolower($value);
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

    public function getAddressAttribute($value)
    {
        return ucwords($value);
    }    



    //Relationships
    public function user() {
        return $this->hasOne('\App\User', 'uid', 'id');
    }

    public function benefits() {
        return $this->hasMany('\App\UserBenefit', 'user_info_id', 'id');
    }

    public function getAllEmployee(){
        $query = DB::table('user_infos')
        ->join('users','users.uid','=','user_infos.id')
        ->join('user_benefits','user_benefits.user_info_id','=','user_infos.id')
        ->join('access_levels','access_levels.id','=','users.access_id')
        ->join('access_level_hierarchies','access_level_hierarchies.child_id','=','user_infos.id')
        ->select('user_infos.id','user_infos.firstname','user_infos.middlename','user_infos.lastname','user_infos.status','user_infos.gender','user_infos.birthdate','user_infos.address','users.email','user_infos.contact_number',DB::raw('max(case when user_benefits.benefit_id = 1 then id_number end) as col1,max(case when user_benefits.benefit_id = 2 then id_number end) as col2,max(case when user_benefits.benefit_id = 3 then id_number end) as col3,max(case when user_benefits.benefit_id = 4 then id_number end) as col4'),'access_levels.name','user_infos.hired_date','user_infos.separation_date')
        ->groupBy('user_infos.id')
        ->orderBy('user_infos.id','asc');
        return $query;
    }

    
}
