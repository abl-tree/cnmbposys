<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
 	protected $table = 'user_reports';

 	protected $fillable = [
        'user_reports_id', 'description',
    ];
}

