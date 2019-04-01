<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class EventTitle extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'event_titles';
    protected $fillable = [
        'title',
        'color',
    ];

    protected $searchable = [
        'title',
        'color',
    ];

    public $timestamps = true;

    protected $rules = [
        'title' => 'sometimes|required|max:500',
        'color' => 'sometimes|required|max:100',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
