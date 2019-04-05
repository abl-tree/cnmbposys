<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use App\Data\Models\BaseModel;

class Benefit extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    
    /**
     * Set the benefit's name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function benefits() {
        return $this->hasOne('\App\Data\Models\UserBenefit', 'benefit_id', 'id');
    }
}
