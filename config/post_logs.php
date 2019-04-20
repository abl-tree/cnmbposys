<?php

return [

    'request_schedules' => [
        'create' => [
            'slug' => 'Successfully created a request schedule for **target_name** [**target_position**] on **start_date** to **end_date**, executed by **logged_name** [**logged_position**]',
        ],
        'update' => [
            'slug' => 'Successfully updated a request schedule with the id [**id**], executed by **logged_name** [**logged_position**]',
        ],
        'delete' => [
            'slug' => 'Successfully deleted a request schedule with the id [**id**], executed by **logged_name** [**logged_position**]',
        ],
    ],

    'events' => [
        'create' => [
            'slug' => 'Successfully created an event [**event_title**], executed by **logged_name** [**logged_position**]',
        ],
        'update' => [
            'slug' => 'Successfully updated an event with the id [**id**], executed by **logged_name** [**logged_position**]',
        ],
        'delete' => [
            'slug' => 'Successfully deleted an event with the id [**id**], executed by **logged_name** [**logged_position**]',
        ],
    ],

];
