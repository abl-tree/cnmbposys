<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class Leave extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'leaves';
    protected $fillable = [
        'user_id',
        'start_event',
        'end_event',
        'leave_type',
        'description',
        'status',
        'generated_by',
        'allowed_access',
    ];

    protected $searchable = [
        'user_id',
        'start_event',
        'end_event',
        'leave_type',
        'description',
        'status',
        'generated_by',
        'allowed_access',
    ];

    public $timestamps = true;

    protected $rules = [
        'user_id' => 'sometimes|required|numeric',
        'start_event' => 'sometimes|required|date',
        'end_event' => 'sometimes|required|date',
        'leave_type' => 'sometimes|required|max:100',
        'description' => 'nullable|max:500',
        'status' => 'in:pending,approved,rejected,cancelled',
        'generated_by' => 'sometimes|required|numeric',
        'allowed_access' => 'sometimes|required|numeric',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
