<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use App\Data\Models\BaseModel;

class Coaching extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $table = 'coaching';
    // protected $appends = [
    //    'verified_by'
    // ];

    protected $fillable = [
        'filed_to', 'filed_by','sched_id','status','remarks','type','img_proof_url','filed_to_action','verified_by','created_at','updated_at'
    ];

    protected $hidden = [
    ];
    

    // public function schedule() {
    //     return $this->belongsTo('\App\Data\Models\AgentSchedule', 'sched_id', 'id');
    // }

    public function filed_by() {
        return $this->belongsTo('\App\Data\Models\UserInfo','filed_by', 'id');
    }
    public function filed_to() {
        return $this->belongsTo('\App\Data\Models\UserInfo','filed_to', 'id');
    }
    public function verified_by() {
        return $this->belongsTo('\App\Data\Models\UserInfo','verified_by', 'id');

        
    }


    // public function getVerifiedbyAttribute(){
    //     $name = null;
    //     if(isset($this->verified_by)){
    //         $name = $this->verified_by->full_name;
    //     }
        
    //     return $name;
    // }
}