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
        'firstname', 'middlename', 'lastname', 'birthdate', 'gender', 'contact_number', 'address', 'image', 'salary_rate','image_ext',
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
        return $this->hasOne('\App\User', 'uid', 'id');
    }

    public function benefits() {
        return $this->hasMany('\App\UserBenefit', 'user_info_id', 'id');
    }
}
