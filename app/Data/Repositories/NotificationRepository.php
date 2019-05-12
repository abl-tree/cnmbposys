<?php
namespace App\Data\Repositories;

use App\User;
use App\Data\Models\Notification;
use App\Data\Models\AgentSchedule;
use App\Data\Models\UserReport;
use App\Data\Models\ReportResponse;
use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\UsersInfoRepository;
use Illuminate\Http\Request;
use Common\Traits\Instances\Response;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Class NotificationRepository
 * @package App\Data\Repositories\Notification
 */
class NotificationRepository extends BaseRepository
{

    protected $notification,
        $user_info_repo;

    public function __construct(
        Notification $notification,
        UsersInfoRepository $usersInfoRepository
    ){
        $this->notification = $notification;
        $this->user_info_repo = $usersInfoRepository;
    }

    /**
     * Builds a notification object.
     *
     * @param array $data
     * @return NotificationRepository
     */
    public function buildNotification($data = []){
        if( !isset($data['sender_id']) ||
            !is_numeric ( $data['sender_id'] ) ||
            $data['sender_id'] <= 0 ){
            return $this->setResponse([
                'code' => 500,
                'title' => "Sender ID is not set."
            ]);
        }

        if( !isset($data['recipient_id']) ||
            !is_numeric ( $data['recipient_id'] ) ||
            $data['recipient_id'] <= 0 ){
            return $this->setResponse([
                'code' => 500,
                'title' => "Recipient ID is not set."
            ]);
        }

        if( !isset($data['type_id']) ||
            !is_numeric ( $data['type_id'] ) ||
            $data['type_id'] <= 0 ){
            return $this->setResponse([
                'code' => 500,
                'title' => "Type ID is not set."
            ]);
        }

        if(!isset($data['type'])){
            return $this->setResponse([
                'code' => 500,
                'title' => "Type is not set."
            ]);
        }

        $type = '';
        $sender = User::with('user_info')->where('id', $data['sender_id'])->first();

        if(isset($sender)){
            $build['sender'] = $sender->user_info->full_name;
        }

        if(strpos($data['type'], 'schedules') !== false){
            $schedule = AgentSchedule::find($data['type_id']);

            if(isset($schedule)){
                $build['start_date'] = $schedule->start_event;
                $build['end_date'] = $schedule->end_event;
            }
        }

        if(strpos($data['type'], 'response') !== false){
            $response = ReportResponse::find($data['type_id']);
            
            if(isset($response)){
                $build['response'] = $response->commitment;
                $build['report_id'] = $response->user_response_id;
            }
        }

        if(strpos($data['type'], 'reports') !== false){
            if(isset($build['report_id'])){
                $report_id = $build['report_id'];
            }else{
                $report_id = $data['type_id'];
            }

            $report = UserReport::find($report_id);
        
            if(isset($report)){
                $build['report'] = $report->description;
            }
        }

        $notification = [
            'sender_id' => $data['sender_id'],
            'recipient_id' => $data['recipient_id'],
            'type' => $data['type'],
            'type_id' => $data['type_id'],
        ];

        $notification['description'] = config('notifications.' . $data['type']);

        /**
         * description builder
         * replace values from config
        */      
        if(strpos($notification['description'], '**sender**') !== false){
            $notification['description'] = str_replace("**sender**", $build['sender'], $notification['description']);
        } 
        //schedules
        if(strpos($notification['description'], '**start_date**') !== false){
            $notification['description'] = str_replace("**start_date**", $build['start_date'], $notification['description']);
        } 
        if(strpos($notification['description'], '**end_date**') !== false){
            $notification['description'] = str_replace("**end_date**", $build['end_date'], $notification['description']);
        }
        //reports
        if(strpos($notification['description'], '**report**') !== false){
            $notification['description'] = str_replace("**report**", $build['report'], $notification['description']);
        }
        //response
        if(strpos($notification['description'], '**response**') !== false){
            $notification['description'] = str_replace("**response**", $build['response'], $notification['description']);
        }

        return $notification;
    }

    /**
     * Defines a notification to the database.
     *
     * @param array $data
     * @return NotificationRepository
     */
    public function defineNotification($data = [])
    {
        //region data validation
        if(!isset($data['id'])){
            
            if( !isset($data['sender_id']) ||
                !is_numeric ( $data['sender_id'] ) ||
                $data['sender_id'] <= 0 ){
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Sender ID is not set."
                ]);
            }

            if( !isset($data['recipient_id']) ||
                !is_numeric ( $data['recipient_id'] ) ||
                $data['recipient_id'] <= 0 ){
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Recipient ID is not set."
                ]);
            }

            if( !isset($data['type_id']) ||
                !is_numeric ( $data['type_id'] ) ||
                $data['type_id'] <= 0 ){
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Type ID is not set."
                ]);
            }

            if(!isset($data['type'])){
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Type is not set."
                ]);
            }

            if(!isset($data['description'])){
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Description is not set."
                ]);
            }

        }
        //endregion data validation

        //region existence check
        if (isset($data['id'])) {
            $does_exist = $this->notification->find($data['id']);

            if (!$does_exist) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Notification does not exist.',
                ]);
            }
        }
        //endregion existence check
        
        //region insertion
        if (isset($data['id'])) {
            $notification = $this->notification->find($data['id']);
        } else {
            $notification = $this->notification->init($this->notification->pullFillable($data));
        }

        if(!$notification->save($data)){
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" =>   [
                    "errors" => $notification->errors(),
                ]
            ]);
        }
        //endregion insertion
        
        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined a notification.",
            "parameters" => $notification,
        ]);
    }

    /**
     * Fetches all available notifications.
     *
     * @param array $data
     * @return NotificationRepository
     */
    public function fetchAllNotifications($data = [])
    {

        $result = $this->notification;

        $meta_index = "notifications";
        $parameters = [];

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "notifications";
            $result = $result->where('recipient_id', $data['id']);
            $parameters['entity_id'] = $data['id'];

        }

        if (isset($data['notification_id']) &&
            is_numeric($data['notification_id'])) {

            $result = $result->where('id', $data['notification_id']);

        }

        if (isset($data['unread']) &&
            $data['unread'] == true){

            $result = $result->whereNull('read_at');
        }

        if (isset($data['all']) &&
            $data['all'] == true){
                
            $result = $result->get()->all();
        }else{
            if(isset($data['limit'])){
                $result = $result->take($data['limit']);
            }

            if(isset($data['offset'])){
                $result = $result->offset($data['offset']);
            }

            $result = $result->get();
        }
        
        if ($result == null) {
            return $this->setResponse([
                'code'       => 404,
                'title'      => "No notifications are found",
                "meta"       => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        foreach($result as $notification){
            if(strpos($notification->type, 'issues') !== false){
                $issue = Issue::find($notification->type_id);
                if(isset($issue)){
                    $notification->project_id = $issue->project_id;
                }
            }

            if(strpos($notification->type, 'accomplishments') !== false){
                $accomplishment = ProjectAccomplishment::find($notification->type_id);
                if(isset($accomplishment)){
                    $notification->project_id = $accomplishment->project_id;
                }
            }

        }

        return $this->setResponse([
            "code"       => 200,
            "title"      => "Successfully retrieved notification",
            "meta"       => [
                $meta_index => $result,
            ],
            "parameters" => $parameters,
        ]);
    }

    /**
     * Marks notification as read.
     *
     * @param array $data
     * @return NotificationRepository
     */
    public function readNotification($data = []){

        $notification = null;

        if (isset($data['id'])) {
            $notification = $this->notification->find($data['id']);

            if (!$notification) {
                return $this->setResponse([
                    'code'  => 500,
                    'title' => 'Notification does not exist.',
                ]);
            }
        }

        $read['status'] = 'read';
        $read['read_at'] = Carbon::now();
        
        if(!$notification->save($read)){
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" =>   [
                    "errors" => $notification->errors(),
                ]
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined a notification.",
            "parameters" => $notification,
        ]);

    }

    /**
     * Defines a new notification and throws a notification event
     *
     * @param array $data
     * @return bool
     */
    public function triggerNotification($data = []){
        $notification = $this->buildNotification($data);
        $define = $this->defineNotification($notification);
        $throw = $this->throwNotification($notification);

        return true;
    }

    /**
     * Throw notification on pusher
     *
     * @param array $data
     * @return void
     */
    public function throwNotification($data = []){
        event(new \App\Events\NotificationEvent($data));
    }
    
}
