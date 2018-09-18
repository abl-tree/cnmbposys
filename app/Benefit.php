<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
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

    public function users() {
        return $this->hasMany('\App\UserBenefit', 'benefit_id', 'id');
    }
}
