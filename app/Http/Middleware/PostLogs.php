<?php

namespace App\Http\Middleware;

use Closure;
use App\Data\Repositories\LogsRepository;
use App\User;

class PostLogs
{

    protected $logs_repo,
        $user;

    public function __construct(
        LogsRepository $logsRepository,
        User $user
    ){
        $this->logs_repo = $logsRepository;
        $this->user = $user;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        if($response->getStatusCode() != 200){
            return $response;
        }

        $data = $request->all();
        $path = $request->getPathInfo();
        $log_data = null;
        $affected_data = null;
        $response_data = $response->getData()->parameters;
        
        if($request->method() == 'POST'){

            if(strpos($path, 'request_schedules') !== false){
                $class_name = 'request_schedules';
                $logged_user = $this->user->find($data['auth_id']);

                if(strpos($path, 'create') !== false){
                    $action = 'create';
                    $target_user = $this->user->find($data['applicant']);
                    $affected_data = config('post_logs.request_schedules.create.slug');
                }
                if(strpos($path, 'update') !== false){
                    $action = 'update';
                    $id = substr($path, strrpos($path, '/') + 1);
                    $data['start_date'] = $response_data->start_date;
                    $data['end_date'] = $response_data->end_date;
                    $target_user = $this->user->find($response_data->applicant);
                    $affected_data = config('post_logs.request_schedules.update.slug');
                }
                if(strpos($path, 'delete') !== false){
                    $action = 'delete';
                    $id = substr($path, strrpos($path, '/') + 1);
                    $data['start_date'] = $response_data->start_date;
                    $data['end_date'] = $response_data->end_date;
                    $target_user = $this->user->find($response_data->applicant);
                    $affected_data = config('post_logs.request_schedules.delete.slug');
                }
            }

            if(strpos($path, 'events') !== false){
                $class_name = 'events';
                $logged_user = $this->user->find($data['auth_id']);

                if(strpos($path, 'create') !== false){
                    $action = 'create';
                    $affected_data = config('post_logs.events.create.slug');
                }
                if(strpos($path, 'update') !== false){
                    $action = 'update';
                    $id = substr($path, strrpos($path, '/') + 1);
                    $data['title'] = $response_data->title;
                    $data['color'] = $response_data->color;
                    $affected_data = config('post_logs.events.update.slug');
                }
                if(strpos($path, 'delete') !== false){
                    $action = 'delete';
                    $id = substr($path, strrpos($path, '/') + 1);
                    $data['title'] = $response_data->title;
                    $data['color'] = $response_data->color;
                    $affected_data = config('post_logs.events.delete.slug');
                }
            }

        // common
        if(strpos($affected_data, '**id**') !== false){
            $affected_data = str_replace("**id**", $id, $affected_data);
        }
        if(strpos($affected_data, '**target_name**') !== false){
            $affected_data = str_replace("**target_name**", $target_user->full_name, $affected_data);
        }
        if(strpos($affected_data, '**target_position**') !== false){
            $affected_data = str_replace("**target_position**", $target_user->access->name, $affected_data);
        }
        if(strpos($affected_data, '**logged_name**') !== false){
            $affected_data = str_replace("**logged_name**", $logged_user->full_name, $affected_data);
        }
        if(strpos($affected_data, '**logged_position**') !== false){
            $affected_data = str_replace("**logged_position**", $logged_user->access->name, $affected_data);
        }

        // additional
        if(strpos($affected_data, '**start_date**') !== false){
            $affected_data = str_replace("**start_date**", $data['start_date'], $affected_data);
        }
        if(strpos($affected_data, '**end_date**') !== false){
            $affected_data = str_replace("**end_date**", $data['end_date'], $affected_data);
        }
        if(strpos($affected_data, '**event_title**') !== false){
            $affected_data = str_replace("**event_title**", $data['title'], $affected_data);
        }
        if(strpos($affected_data, '**color**') !== false){
            $affected_data = str_replace("**color**", $data['color'], $affected_data);
        }

        if($affected_data){

            $log_data = [
                "user_id" => $data['auth_id'],
                "action" => $action,
                "affected_data" => $affected_data
            ];
    
            $log = $this->logs_repo->logsInputCheck($log_data);
        }

        }

        return $response;
    }
}
