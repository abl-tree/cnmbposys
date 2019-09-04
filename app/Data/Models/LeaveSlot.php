<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class LeaveSlot extends BaseModel
{

    protected $primaryKey = 'id';
    protected $table = 'leave_slots';
    protected $fillable = [
        'user_id',
        'leave_type',
        'value',
        'original_value',
        'date',
    ];

    protected $searchable = [
        'user_id',
        'leave_type',
        'value',
        'original_value',
        'date',
    ];

    public $timestamps = true;

    protected $rules = [
        'user_id' => 'sometimes|required|numeric',
        'leave_type' => 'sometimes|required|max:100',
        'value' => 'sometimes|required|numeric',
        'original_value' => 'sometimes|required|numeric',
        'date' => 'sometimes|required|date',
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
