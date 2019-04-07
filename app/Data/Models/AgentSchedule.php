<?php

namespace App\Data\Models;

use App\Data\Models\BaseModel;
use DB;
use Carbon\Carbon;

class AgentSchedule extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'agent_schedules';
    protected $fillable = [
        'user_id',
        'title_id',
        'start_event',
        'end_event',
    ];

    protected $appends = [
        'rendered_hours', 
        'is_working',
        'is_present',
        'break'
    ];

    protected $searchable = [
        'user_id',
        'title_id',
        'start_event',
        'end_event', 
    ];

    public $timestamps = true;

    protected $rules = [
        'user_id' => 'sometimes|required|numeric',
        'title_id' => 'sometimes|required|numeric',
        'start_event' => 'sometimes|required|date',
        'end_event' => 'sometimes|required|date',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function getRenderedHoursAttribute() {
        $rendred_time = 0;

        if($this->attendances->count()) {
            foreach ($this->attendances as $key => $value) {
                $rendred_time += $value->rendered_time;
            }
        }

        return gmdate('H:i:s', $rendred_time);
    }

    public function getIsWorkingAttribute() {
        if($this->attendances->count()) {
            foreach ($this->attendances as $key => $value) {
                if($value->time_out == null) {
                    return 1;
                }
            }
        }

        return 0;
    }

    public function getIsPresentAttribute() {
        if($this->attendances->count()) {
            return 1;
        }

        return 0;
    }

    public function getBreakAttribute() {
        $data = array(
            'remaining' => 3, 
            'spent' => array(
                    'description' => 'Time spent for 3 breaks.', 
                    'time' => 0
                ),
            'total' => 0
        );

        if($this->attendances->count()) {
            $break_duration = 0;

            foreach ($this->attendances as $key => $value) {
                if($value->time_out == null) {
                    break;
                }

                if($this->attendances->count() === $key + 1) {    
                    $out = Carbon::parse($value->time_out);
                    
                    $break_duration += $in->diffInSeconds(Carbon::now());
                } else {
                    $out = Carbon::parse($value->time_out);
                    $in = Carbon::parse($this->attendances[$key + 1]->time_in);
                    
                    $break_duration += $in->diffInSeconds($out);
                }

                if($key + 1 === 3) {
                    $data['spent']['time'] = gmdate('H:i:s', $break_duration);
                }
            }
            
            $data['remaining'] -= ($this->attendances->count() - 1);
            $data['total'] = gmdate('H:i:s', $break_duration);
        }

        return $data;
    }

    public function attendances(){
        return $this->hasMany('App\Data\Models\Attendance', 'schedule_id');
    }

    public function agentschedule(){
        return $this->belongsTo('App\Data\Models\User');
    }

    public function user_info(){
        return $this->hasOne('App\Data\Models\UserInfo',"id", "user_id" );
    }

    public function title(){
        return $this->hasOne('App\Data\Models\EventTitle',"id", "title_id" );
    }
}
