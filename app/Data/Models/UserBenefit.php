<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use App\Data\Models\BaseModel;

class UserBenefit extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_info_id', 'benefit_id', 'id_number','created_at','updated_at'
    ];

    public function info() {
        return $this->belongsTo('\App\Data\Models\UserInfo', 'id', 'user_info_id')->with('id_number','benefit_id');
    }

    public function benefit() {
        return $this->belongsTo('\App\Data\Models\Benefit', 'id', 'benefit_id');
    }
}
