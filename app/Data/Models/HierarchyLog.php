<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;
use Illuminate\Support\Carbon;
class HierarchyLog extends BaseModel
{
    protected $fillable = [
        'parent_id', 'child_id',
        'start_date', 'end_date',
    ];

    protected $appends = ["tmp_end_date"];

    protected $searchable = [
        "parent_details.firstname",
        "parent_details.middlename",
        "parent_details.lastname",
        "child_details.firstname",
        "child_details.middlename",
        "child_details.lastname",
    ];

    public function parent_details(){
        return $this->hasOne("App\Data\Models\UserInfo","id","parent_id");
    }


    public function parent_user_details(){
        return $this->hasOne("App\User","uid","parent_id");
    }

    public function child_details(){
        return $this->hasOne("App\Data\Models\UserInfo","id","child_id");
    }


    public function child_user_details(){
        return $this->hasOne("App\User","uid","child_id");
    }

    public function getTmpEndDateAttribute($value){
        return $this->end_date!=null ? $this->end_date : Carbon::now()->endOfDay()->toDateTimeString();
    }

    public function setEndDateAttribute($value){
        $this->attributes["end_date"] = $value? Carbon::parse($value)->endOfDay()->toDateTimeString():null;
    }

    public function setStartDateAttribute($value){
        $this->attributes["start_date"] = $value? Carbon::parse($value)->startOfDay()->toDateTimeString():null;
    }
}