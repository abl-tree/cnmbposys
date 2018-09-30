<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBenefit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_info_id', 'benefit_id', 'id_number',
    ];

    public function info() {
        return $this->belongsTo('\App\UserInfo', 'id', 'user_info_id')->with('id_number','benefit_id');
    }

    public function benefit() {
        return $this->belongsTo('\App\Benefit', 'id', 'benefit_id');
    }
}
