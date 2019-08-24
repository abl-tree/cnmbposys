<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;

class Notification extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'notifications';

    /**
     * list of fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'type',
        'type_id',
        'sender_id',
        'recipient_id',
        'status',
        'endpoint',
        'read_at',
    ];

    /**
     * lsit of validators
     *
     * @var array
     */
    protected $rules = [
        'description' => 'sometimes|required',
        'endpoint' => 'sometimes|required|max:191',
        'type' => 'sometimes|required|max:191',
        'type_id' => 'sometimes|required|numeric',
        'sender_id' => 'sometimes|required|numeric',
        'recipient_id' => 'sometimes|required|numeric',
        'status' => 'sometimes|required|max:191',
        'read_at' => 'nullable|date',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * user-notification relation sender_id
     *
     * @return void
     */
    public function sender()
    {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'sender_id');
    }

    /**
     * user-notification relation recepient_id
     *
     * @return void
     */
    public function recipient()
    {
        return $this->hasOne('\App\Data\Models\UserInfo', 'id', 'recipient_id');
    }

}
