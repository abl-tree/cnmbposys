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
        'approved_by',
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
        'approved_by',
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

    protected $hidden = ['deleted_at'];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo('\App\User', 'user_id', 'id');
    }
    public function generated_by()
    {
        return $this->belongsTo('\App\User', 'generated_by', 'id');
    }
    public function approved_by()
    {
        return $this->belongsTo('\App\User', 'approved_by', 'id');
    }
    public function leave_credits()
    {
        return $this->hasMany('\App\Data\Models\LeaveCredit', 'user_id', 'user_id');
    }

    /**
     * Accessors
     */
    public function getRecentlyApprovedAttribute()
    {
        return $this->where('user_id', $this->user_id)
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->first();
    }
}
