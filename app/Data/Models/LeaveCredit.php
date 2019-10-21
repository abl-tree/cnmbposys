<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class LeaveCredit extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'leave_credits';
    protected $fillable = [
        'user_id',
        'leave_type',
        'value',
    ];

    protected $searchable = [
        'user_id',
        'leave_type',
        'value',
        'user.firstname',
        'user.middlename',
        'user.lastname',
    ];

    public $timestamps = true;

    protected $rules = [
        'user_id' => 'sometimes|required|numeric',
        'leave_type' => 'sometimes|required|max:100',
        'value' => 'sometimes|required|numeric',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo('\App\Data\Models\UserInfo', 'user_id', 'id');
    }
}
