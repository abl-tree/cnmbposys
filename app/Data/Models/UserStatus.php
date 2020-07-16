<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use App\Data\Models\BaseModel;

class UserStatus extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $table = 'user_status';
    // protected $appends = [
    //    'value','name'
    // ];

    protected $fillable = [
        'status', 'type', 'description','created_at','updated_at'
    ];
    protected $searchable = [
        'type','status'
        
    ];
    // public function info() {
    //     return $this->belongsTo('\App\Data\Models\UserInfo', 'id', 'user_info_id')->with('id_number','benefit_id');
    // }

    // public function benefit() {
    //     return $this->belongsTo('\App\Data\Models\Benefit', 'id', 'benefit_id');
    // }
}
