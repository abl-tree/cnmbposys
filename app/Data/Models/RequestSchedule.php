<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class RequestSchedule extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'request_schedules';
    protected $fillable = [
        'applicant',
        'start_date',
        'end_date',
        'title_id',
        'requested_by',
        'managed_by',
        'rta_remarks',
        'response_date',
        'status',
        'mark',
    ];

    protected $searchable = [
        'applicant',
        'start_date',
        'end_date',
        'title_id',
        'requested_by',
        'managed_by',
        'rta_remarks',
        'response_date',
        'status',
        'mark',
    ];

    public $timestamps = true;

    protected $rules = [
        'applicant' => 'sometimes|required|integer',
        'start_date' => 'sometimes|required|date',
        'end_date' => 'sometimes|required|date',
        'title_id' => 'sometimes|required|integer',
        'requested_by' => 'sometimes|required|integer',
        'managed_by' => 'sometimes|required|integer',
        'rta_remarks' => 'nullable|max:191',
        'response_date' => 'nullable|date',
        'status' => 'sometimes|required|max:191',
        'mark' => 'nullable|max:191',
    ];

    protected $hidden = ['deleted_at', 'title_id'];

    public function applicant()
    {
        return $this->hasOne('App\Data\Models\UserInfo', "id", "applicant");
    }

    public function requested_by()
    {
        return $this->hasOne('App\Data\Models\UserInfo', "id", "requested_by");
    }

    public function managed_by()
    {
        return $this->hasOne('App\Data\Models\UserInfo', "id", "managed_by");
    }

    public function title()
    {
        return $this->hasOne('App\Data\Models\EventTitle', 'id', 'title_id');
    }
}
