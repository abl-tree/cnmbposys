<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'firstname', 'middlename', 'lastname', 'age', 'gender', 'contact_number', 'image', 'salary_rate',
    ];
    
    /**
     * Set the user's firstname.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = ucwords($value);
    }
    
    /**
     * Set the user's middlename.
     *
     * @param  string  $value
     * @return void
     */
    public function setMiddlenameAttribute($value)
    {
        $this->attributes['middlename'] = ucwords($value);
    }
    
    /**
     * Set the user's middlename.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = ucwords($value);
    }

    public function user() {
        return $this->hasOne('\App\User', 'id', 'uid');
    }

    public function benefits() {
        return $this->hasMany('\App\UserBenefit', 'user_info_id', 'id');
    }
}
