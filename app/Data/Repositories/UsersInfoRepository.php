<?php
namespace App\Data\Repositories;

use App\Data\Models\AccessLevel;
use App\Data\Models\AccessLevelHierarchy;
use App\Data\Models\BenefitUpdate;
use App\Data\Models\HierarchyLog;
use App\Data\Models\HierarchyUpdate;
use App\Data\Models\UpdateStatus;
use App\Data\Models\UserBenefit;
use App\Data\Models\UserCluster;
use App\Data\Models\UserInfo;
use App\Data\Models\Users;
use App\Data\Models\UsersData;
use App\Data\Models\UsersUpdate;
use App\Data\Repositories\BaseRepository;
use App\Data\Repositories\LogsRepository;
use App\User;
use ArrayObject;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Storage;

class UsersInfoRepository extends BaseRepository
{

    protected $user_info, $user_datum, $user_status, $user_benefits, $user_infos, $logs,
    $user, $access_level_hierarchy, $benefit_update, $hierarchy_update, $user_data_update,
    $access_level, $hierarchy_log;

    public function __construct(
        UsersData $user_info,
        BenefitUpdate $benefit_update,
        HierarchyUpdate $hierarchy_update,
        UserInfo $user_infos,
        User $user,
        Users $user_datum,
        UsersUpdate $user_data_update,
        UpdateStatus $user_status,
        UserCluster $select_users,
        UserBenefit $user_benefits,
        LogsRepository $logs_repo,
        AccessLevelHierarchy $access_level_hierarchy,
        AccessLevel $access_level,
        HierarchyLog $hierarchy_log
    ) {
        $this->user_info = $user_info;
        $this->user_infos = $user_infos;
        $this->benefit_update = $benefit_update;
        $this->hierarchy_update = $hierarchy_update;
        $this->user = $user;
        $this->user_datum = $user_datum;
        $this->user_data_update = $user_data_update;
        $this->user_status = $user_status;
        $this->select_users = $select_users;
        $this->user_benefits = $user_benefits;
        $this->logs = $logs_repo;
        $this->access_level_hierarchy = $access_level_hierarchy;
        $this->access_level = $access_level;
        $this->hierarchy_log = $hierarchy_log;

        $this->no_sort = [
            'full_name',
            'email',
            'position',
        ];
    }
    public function usersInfo($data = [])
    {
        $meta_index = "metadata";
        $parameters = [];
        $count = 0;
        if (isset($data['id']) &&
            is_numeric($data['id'])) {
            $meta_index = "metadata";
            $data['single'] = false;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];
            $parameters['id'] = $data['id'];
        }

        $data['where'][] = [
            "target" => "status",
            "operator" => "=",
            "value" => 'active',
        ];

        if (isset($data['leaves'])) {
            if (isset($data['start_date']) && isset($data['end_date'])) {
                if (isset($data['leave_type'])) {
                    $data['wherehas'][] = [
                        'relation' => 'leave_checker',
                        'target' => [
                            [
                                'column' => 'start_event',
                                'operator' => '>=',
                                'value' => $data['start_date'],
                            ],
                            [
                                'column' => 'start_event',
                                'operator' => '<=',
                                'value' => $data['end_date'],
                            ],
                            [
                                'column' => 'leave_type',
                                'value' => $data['leave_type'],
                            ],
                        ],
                    ];
                } else {
                    $data['wherehas'][] = [
                        'relation' => 'leave_checker',
                        'target' => [
                            [
                                'column' => 'start_event',
                                'operator' => '>=',
                                'value' => $data['start_date'],
                            ],
                            [
                                'column' => 'start_event',
                                'operator' => '<=',
                                'value' => $data['end_date'],
                            ],
                        ],
                    ];
                }
            } else if (isset($data['leave_type'])) {
                $data['wherehas'][] = [
                    'relation' => 'leave_checker',
                    'target' => [
                        [
                            'column' => 'leave_type',
                            'value' => $data['leave_type'],
                        ],
                    ],
                ];
            } else {
                $data['wherehas'][] = [
                    'relation' => 'leave_checker',
                    'target' => [],
                ];
            }
        }
        if (isset($data['leave_credits'])) {
            if (isset($data['leave_type'])) {
                $data['wherehas'][] = [
                    'relation' => 'leave_credit_checker',
                    'target' => [
                        [
                            'column' => 'leave_type',
                            'value' => $data['leave_type'],
                        ],
                    ],
                ];
            } else {
                $data['wherehas'][] = [
                    'relation' => 'leave_credit_checker',
                    'target' => [],
                ];
            }
        }
        if (isset($data['leave_slots'])) {
            if (isset($data['start_date']) && isset($data['end_date'])) {
                if (isset($data['leave_type'])) {
                    $data['wherehas'][] = [
                        'relation' => 'leave_slot_checker',
                        'target' => [
                            [
                                'column' => 'date',
                                'operator' => '>=',
                                'value' => $data['start_date'],
                            ],
                            [
                                'column' => 'date',
                                'operator' => '<=',
                                'value' => $data['end_date'],
                            ],
                            [
                                'column' => 'leave_type',
                                'value' => $data['leave_type'],
                            ],
                        ],
                    ];
                } else if (isset($data['leave_type'])) {
                    $data['wherehas'][] = [
                        'relation' => 'leave_slot_checker',
                        'target' => [
                            [
                                'column' => 'leave_type',
                                'value' => $data['leave_type'],
                            ],
                        ],
                    ];
                } else {
                    $data['wherehas'][] = [
                        'relation' => 'leave_slot_checker',
                        'target' => [
                            [
                                'column' => 'date',
                                'operator' => '>=',
                                'value' => $data['start_date'],
                            ],
                            [
                                'column' => 'date',
                                'operator' => '<=',
                                'value' => $data['end_date'],
                            ],
                        ],
                    ];
                }
            } else {
                $data['wherehas'][] = [
                    'relation' => 'leave_slot_checker',
                    'target' => [],
                ];
            }
        }

        if (isset($data['target'])) {
            $data['where'] = [
                [
                    "target" => "excel_hash",
                    "operator" => "!=",
                    "value" => "development",
                ],
            ];
            $result = $this->user_info;
            $data['relations'] = ["user_info", "accesslevel", "benefits"];
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'firstname';
                    $data['target'][] = 'middlename';
                    $data['target'][] = 'lastname';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "gender")) {
                    $data['wherehas'][] = [
                        'relation' => 'user_info',
                        'target' => [
                            [
                                'column' => 'gender',
                                'operator' => '=',
                                'value' => $data['query'],
                            ],

                        ],
                    ];
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "position")) {
                    $access = AccessLevel::all();
                    $access_id = [];
                    foreach ($access as $key => $value) {
                        if (strpos(strtolower(str_replace(' ', '', $value->code)), strtolower(str_replace(' ', '', $data['query']))) !== false) {
                            array_push($access_id, $value->id);
                            // $countni++;
                        }

                    }
                    $data['wherehas'][] = [
                        'relation' => 'user_info',
                        'target' => [
                            [
                                'column' => 'access_id',
                                'operator' => 'wherein',
                                'value' => $access_id,
                            ],

                        ],
                    ];
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "access_id")) {
                    $array = [];
                    $countresult = 0;
                    $data['target'][] = 'user_info.access_id';
                    unset($data['target'][$index]);
                    $results = $this->genericSearch($data, $result)->get()->all();
                    foreach ($results as $key => $value) {
                        if ($value->access_id == $data['query']) {
                            array_push($array, $value);
                            $countresult += 1;
                        }
                    }
                    if ($array != []) {
                        return $this->setResponse([
                            "code" => 200,
                            "title" => "Successfully searched Users",
                            "meta" => [
                                $meta_index => $array,
                                "count" => $countresult,
                            ],
                            "parameters" => $parameters,
                        ]);
                    } else {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "No user are found",
                            "meta" => [
                                $meta_index => $array,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }

                }
                if (str_contains($column, "p_email")) {
                    $data['target'][] = 'p_email';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "email")) {
                    $data['target'][] = 'user_info.email';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "status")) {
                    $data['target'][] = 'status';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "type")) {
                    $data['target'][] = 'type';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "address")) {
                    $data['target'][] = 'address';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "birthdate")) {
                    $data['target'][] = 'birthdate';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "hired_date")) {
                    $count = 0;
                    $array = json_decode($data['query'], true);
                    $general = [];
                    $start = new DateTime($array[0]);
                    $end = (new DateTime($array[1]))->modify('+1 day');
                    $interval = new DateInterval('P1D');
                    $period = new DatePeriod($start, $interval, $end);
                    foreach ($period as $dt) {
                        // array_push($date,$dt->format("Y-m-d"));
                        $data['query'] = $dt->format("Y-m-d");
                        $data['target'][] = 'hired_date';
                        unset($data['target'][$index]);
                        // $count_data = $data;
                        $results = $this->genericSearch($data, $result)->get()->all();
                        if ($results) {
                            array_push($general, $results);
                            $count += 1;
                        }

                    }
                    $new = [];
                    while ($item = array_shift($general)) {
                        array_push($new, ...$item);
                    }
                    if ($result == null) {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "No user are found",
                            "meta" => [
                                $meta_index => $new,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }

                    $count_data['search'] = true;
                    // $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

                    return $this->setResponse([
                        "code" => 200,
                        "title" => "Successfully searched Users",
                        "meta" => [
                            $meta_index => $new,
                            "count" => $count,
                        ],
                        "parameters" => $parameters,
                    ]);
                }
                if (str_contains($column, "separation_date")) {
                    $count = 0;
                    $array = json_decode($data['query'], true);
                    $general = [];
                    $start = new DateTime($array[0]);
                    $end = (new DateTime($array[1]))->modify('+1 day');
                    $interval = new DateInterval('P1D');
                    $period = new DatePeriod($start, $interval, $end);
                    foreach ($period as $dt) {
                        // array_push($date,$dt->format("Y-m-d"));
                        $data['query'] = $dt->format("Y-m-d");
                        $data['target'][] = 'separation_date';
                        unset($data['target'][$index]);

                        // $count_data = $data;
                        $results = $this->genericSearch($data, $result)->get()->all();
                        if ($results) {
                            array_push($general, $results);
                            $count += 1;
                        }

                    }
                    $new = [];
                    while ($item = array_shift($general)) {
                        array_push($new, ...$item);
                    }
                    if ($result == null) {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "No user are found",
                            "meta" => [
                                $meta_index => $new,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }

                    $count_data['search'] = true;
                    // $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

                    return $this->setResponse([
                        "code" => 200,
                        "title" => "Successfully searched Users",
                        "meta" => [
                            $meta_index => $new,
                            "count" => $count,
                        ],
                        "parameters" => $parameters,
                    ]);
                }
            }
            $count_data = $data;
            $result = $this->genericSearch($data, $result)->get()->all();

            if ($result == null) {
                return $this->setResponse([
                    'code' => 404,
                    'title' => "No user are found",
                    "meta" => [
                        $meta_index => $result,
                    ],
                    "parameters" => $parameters,
                ]);
            }

            $count_data['search'] = true;
            $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully searched Users",
                "meta" => [
                    $meta_index => $result,
                    "count" => $count,
                ],
                "parameters" => $parameters,
            ]);
        }
        $count_data = $data;
        $data['relations'] = ["user_info", "accesslevel", "benefits", "leaves", "leave_credits", "leave_slots"];
        $data['where'] = [
            [
                "target" => "excel_hash",
                "operator" => "!=",
                "value" => "development",
            ],
        ];
        $result = $this->fetchGeneric($data, $this->user_info);
        $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));
        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No Users are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved users Informations",
            "description" => "UserInfo",
            "meta" => [
                $meta_index => $result,
                "count" => $count - 1,
            ],
            "parameters" => $parameters,

        ]);
    }

    public function leavesByTlOm($data = [])
    {

        $meta_index = "metadata";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "metadata";
            $data['single'] = false;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['id'] = $data['id'];

        }

        $data['where'][] = [
            "target" => "status",
            "operator" => "=",
            "value" => 'active',
        ];

        if (isset($data['leaves'])) {
            $target_data = [];

            if (isset($data['start_date'])) {
                $target_data[] = [
                    'column' => 'created_at',
                    'operator' => '>=',
                    'value' => $data['start_date'],
                ];
            }
            if (isset($data['end_date'])) {
                $target_data[] = [
                    'column' => 'created_at',
                    'operator' => '<=',
                    'value' => $data['end_date'],
                ];
            }
            if (isset($data['status'])) {
                $target_data[] = [
                    'column' => 'status',
                    'operator' => '=',
                    'value' => $data['status'],
                ];
            }
            if (isset($data['leave_type'])) {
                $target_data[] = [
                    'column' => 'leave_type',
                    'operator' => '=',
                    'value' => $data['leave_type'],
                ];
            }
            if (isset($data['allowed_access'])) {
                $target_data[] = [
                    'column' => 'allowed_access',
                    'operator' => '=',
                    'value' => $data['allowed_access'],
                ];
            }
            if (isset($data['tl'])) {
                $data['wherehas'][] = [
                    'relation' => 'tl_schedule_checker.leave',
                    'target' => $target_data,
                ];
            } else if (isset($data['om'])) {
                $data['wherehas'][] = [
                    'relation' => 'om_schedule_checker.leave',
                    'target' => $target_data,
                ];
            } else {
                $data['wherehas'][] = [
                    'relation' => 'leave_checker',
                    'target' => $target_data,
                ];
            }

        }

        if (isset($data['target'])) {

            $result = $this->user_info;
            $data['relations'] = ["user_info", "accesslevel"];

            if (isset($data['no_relations'])) {
                unset($data['relations']);
            }

            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'firstname';
                    $data['target'][] = 'middlename';
                    $data['target'][] = 'lastname';
                    unset($data['target'][$index]);
                }
            }

            $count_data = $data;
            $result = $this->genericSearch($data, $result)->get()->all();

            if ($result == null) {
                return $this->setResponse([
                    'code' => 404,
                    'title' => "No users are found",
                    "meta" => [
                        $meta_index => $result,
                    ],
                    "parameters" => $parameters,
                ]);
            }

            $count_data['search'] = true;
            $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully searched Users",
                "meta" => [
                    $meta_index => $result,
                    "count" => $count,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data = $data;
        $data['relations'] = ["user_info", "accesslevel", "leaves", "leave_credits", "leave_slots"];

        if (isset($data['no_relations'])) {
            unset($data['relations']);
        }

        $result = $this->fetchGeneric($data, $this->user_info);
        $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No Users are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved users",
            "description" => "UserInfo",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,

        ]);
    }

    /**
     * Fetch all users with leaves
     *
     * @param array $data
     * @return mixed
     */
    public function usersWithLeaves($data = [])
    {
        $meta_index = "metadata";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "metadata";
            $data['single'] = false;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['id'] = $data['id'];

        }

        $data['where'][] = [
            "target" => "status",
            "operator" => "=",
            "value" => 'active',
        ];

        if (isset($data['leaves'])) {
            $target_data = [];

            if (isset($data['start_date'])) {
                $target_data[] = [
                    'column' => 'start_event',
                    'operator' => '>=',
                    'value' => $data['start_date'],
                ];
            }
            if (isset($data['end_date'])) {
                $target_data[] = [
                    'column' => 'start_event',
                    'operator' => '<=',
                    'value' => $data['end_date'],
                ];
            }
            if (isset($data['created_start_date'])) {
                $target_data[] = [
                    'column' => 'created_at',
                    'operator' => '>=',
                    'value' => $data['created_start_date'],
                ];
            }
            if (isset($data['created_end_date'])) {
                $target_data[] = [
                    'column' => 'created_at',
                    'operator' => '<=',
                    'value' => $data['created_end_date'],
                ];
            }
            if (isset($data['status'])) {
                $target_data[] = [
                    'column' => 'status',
                    'operator' => '=',
                    'value' => $data['status'],
                ];
            }
            if (isset($data['leave_type'])) {
                $target_data[] = [
                    'column' => 'leave_type',
                    'operator' => '=',
                    'value' => $data['leave_type'],
                ];
            }
            if (isset($data['allowed_access'])) {
                $target_data[] = [
                    'column' => 'allowed_access',
                    'operator' => '=',
                    'value' => $data['allowed_access'],
                ];
            }

            if (isset($data['om_id'])) {
                $data['wherehas'][] = [
                    'relation' => 'leave_checker.schedule',
                    'target' => [
                        [
                            'column' => 'om_id',
                            'operator' => '=',
                            'value' => $data['om_id'],
                        ],
                    ],
                ];
            }

            if (isset($data['tl_id'])) {
                $data['wherehas'][] = [
                    'relation' => 'leave_checker.schedule',
                    'target' => [
                        [
                            'column' => 'tl_id',
                            'operator' => '=',
                            'value' => $data['tl_id'],
                        ],
                    ],
                ];
            }

            $data['wherehas'][] = [
                'relation' => 'leave_checker',
                'target' => $target_data,
            ];

            $data['where_relations'][] = [
                'relation' => 'leaves',
                'target' => $target_data,
            ];
        }

        if (isset($data['leave_credits'])) {
            $target_data = [];

            if (isset($data['leave_type'])) {
                $target_data[] = [
                    'column' => 'leave_type',
                    'operator' => '=',
                    'value' => $data['leave_type'],
                ];
            }

            $data['wherehas'][] = [
                'relation' => 'leave_credit_checker',
                'target' => $target_data,
            ];

            $data['where_relations'][] = [
                'relation' => 'leave_credits',
                'target' => $target_data,
            ];
        }

        if (isset($data['leave_slots'])) {
            $target_data = [];

            if (isset($data['start_date'])) {
                $target_data[] = [
                    'column' => 'date',
                    'operator' => '>=',
                    'value' => $data['start_date'],
                ];
            }
            if (isset($data['end_date'])) {
                $target_data[] = [
                    'column' => 'date',
                    'operator' => '<=',
                    'value' => $data['end_date'],
                ];
            }
            if (isset($data['leave_type'])) {
                $target_data[] = [
                    'column' => 'leave_type',
                    'operator' => '=',
                    'value' => $data['leave_type'],
                ];
            }

            $data['wherehas'][] = [
                'relation' => 'leave_slot_checker',
                'target' => $target_data,
            ];

            $data['where_relations'][] = [
                'relation' => 'leave_slots',
                'target' => $target_data,
            ];
        }

        if (isset($data['target'])) {

            $result = $this->user_info;
            $data['relations'] = ["user_info", "accesslevel"];

            if (isset($data['no_relations'])) {
                unset($data['relations']);
            }

            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'firstname';
                    $data['target'][] = 'middlename';
                    $data['target'][] = 'lastname';
                    unset($data['target'][$index]);
                }
            }

            $count_data = $data;
            $result = $this->genericSearch($data, $result)->get()->all();

            if ($result == null) {
                return $this->setResponse([
                    'code' => 404,
                    'title' => "No users are found",
                    "meta" => [
                        $meta_index => $result,
                    ],
                    "parameters" => $parameters,
                ]);
            }

            $count_data['search'] = true;
            $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully searched Users",
                "meta" => [
                    $meta_index => $result,
                    "count" => $count,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data = $data;
        $data['relations'] = ["user_info", "accesslevel"];

        if (!isset($data['leaves'])) {
            $data['relations'][] = 'leaves';
        }
        if (!isset($data['leave_credits'])) {
            $data['relations'][] = 'leave_credits';
        }
        if (!isset($data['leave_slots'])) {
            $data['relations'][] = 'leave_slots';
        }

        if (isset($data['no_relations'])) {
            unset($data['relations']);
        }

        $result = $this->fetchGeneric($data, $this->user_info);
        $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No Users are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        foreach ($result as $user) {
            $user->last_approved_leave = isset($user->leave_checker) ? $user->leave_checker->recently_approved : null;
            unset($user->leave_checker);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved users",
            "description" => "UserInfo",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,

        ]);
    }

    /**
     * Fetch all users with leaves
     *
     * @param array $data
     * @return mixed
     */
    public function usersWithSchedules($data = [])
    {
        $meta_index = "metadata";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "metadata";
            $data['single'] = false;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['id'] = $data['id'];

        }

        $data['where'][] = [
            "target" => "status",
            "operator" => "=",
            "value" => 'active',
        ];

        //where_relations data
        $target_where_relations = [];

        if (isset($data['start_date'])) {
            $target_where_relations[] = [
                'column' => 'start_event',
                'operator' => '>=',
                'value' => $data['start_date'],
            ];
        }

        if (isset($data['end_date'])) {
            $target_where_relations[] = [
                'column' => 'start_event',
                'operator' => '<=',
                'value' => $data['end_date'],
            ];
        }

        $data['where_relations'][] = [
            'relation' => 'tl_schedules',
            'target' => $target_where_relations,
        ];

        $data['where_relations'][] = [
            'relation' => 'om_schedules',
            'target' => $target_where_relations,
        ];

        //wherehas data
        $target_data = [];

        if (isset($data['start_date'])) {
            $target_data[] = [
                'column' => 'start_event',
                'operator' => '>=',
                'value' => $data['start_date'],
            ];
        }
        if (isset($data['end_date'])) {
            $target_data[] = [
                'column' => 'start_event',
                'operator' => '<=',
                'value' => $data['end_date'],
            ];
        }
        if (isset($data['approved'])) {
            $target_data[] = [
                'column' => 'approved_by',
                'operator' => 'not_null',
            ];
        }
        if (isset($data['om_id']) && $data['om_id'] != null) {
            $target_data[] = [
                'column' => 'om_id',
                'operator' => '=',
                'value' => $data['om_id'],
            ];
        }

        if (isset($data['tl'])) {
            $data['wherehas'][] = [
                'relation' => 'tl_schedule_checker',
                'target' => $target_data,
            ];
        }
        if (isset($data['om'])) {
            $data['wherehas'][] = [
                'relation' => 'om_schedule_checker',
                'target' => $target_data,
            ];
        }

        if (isset($data['target'])) {

            $result = $this->user_info;
            // $data['relations'] = ["tl_schedules", "om_schedules"];
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'firstname';
                    $data['target'][] = 'middlename';
                    $data['target'][] = 'lastname';
                    unset($data['target'][$index]);
                }
            }

            if (isset($data['no_relations'])) {
                unset($data['relations']);
            }

            $count_data = $data;
            $result = $this->genericSearch($data, $result)->get()->all();

            if ($result == null) {
                return $this->setResponse([
                    'code' => 404,
                    'title' => "No users are found",
                    "meta" => [
                        $meta_index => $result,
                    ],
                    "parameters" => $parameters,
                ]);
            }

            $count_data['search'] = true;
            $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully searched Users",
                "meta" => [
                    $meta_index => $result,
                    "count" => $count,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data = $data;
        // $data['relations'] = ["tl_schedules", "om_schedules"];

        if (isset($data['no_relations'])) {
            unset($data['where_relations']);
        }

        $result = $this->fetchGeneric($data, $this->user_info);
        $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No Users are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved users",
            "description" => "UserInfo",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,

        ]);
    }

    public function fetchUser($data = [])
    {
        $meta_index = "metadata";
        $parameters = [];
        $count = 0;

        $data["relations"][] = "benefits";

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "metadata";
            $data['single'] = false;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['id'] = $data['id'];

        }
        $count_data = $data;
        $result = $this->fetchGeneric($data, $this->user_info);
        $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No Users are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }
        if ($result[0]->excel_hash == "development") {
            return $this->setResponse([
                'code' => 404,
                'title' => "No Users are found",
                "meta" => [
                    $meta_index => [],
                ],
                "parameters" => $parameters,
            ]);
        }
        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved users Informations",
            "description" => "UserInfo",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,

        ]);
    }

    public function logsInputCheck($data = [])
    {
        // data validation

        if (!isset($data['user_id']) ||
            !is_numeric($data['user_id']) ||
            $data['user_id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is not set.",
            ]);
        }

        if (!isset($data['action'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "action ID is not set.",
            ]);
        }

        if (!isset($data['affected_data'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "data affected is not set.",
            ]);
        }

        $action_logs = $this->action_logs->init($this->action_logs->pullFillable($data));
        $action_logs->save($data);

        if (!$action_logs->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $action_logs->errors(),
                ],
            ]);
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully defined an agent schedule.",
            "parameters" => $action_logs,
        ]);

    }

    public function addUser($data = [])
    {
        // data validation
        $error_array = new ArrayObject();
        $error_count = 0;
        $action = null;
        $user_datani = [];
        $user_information = [];
        $hierarchy = [];
        $cluster = [];
        $user_benefits = [];
        $benefits = [];
        $auth_id = auth()->user()->id;
        $auth = $this->user->find($auth_id);
        if (!isset($data['id'])) {
            if (!isset($data['firstname'])) {
                $error_array->offsetSet('firstname', "The username field is required.");
                $error_count++;
            } else {
                $user_information['firstname'] = $data['firstname'];
            }
            if (!isset($data['middlename'])) {
                $error_array->offsetSet('middlename', "The middlename field is required.");
                $error_count++;
            } else {
                $user_information['middlename'] = $data['middlename'];
            }
            if (!isset($data['lastname'])) {
                $error_array->offsetSet('lastname', "The lastname field is required.");
                $error_count++;
            } else {
                $user_information['lastname'] = $data['lastname'];
            }
            if (!isset($data['birthdate'])) {
                $error_array->offsetSet('birthdate', "The birthdate field is required.");
                $error_count++;
            } else {
                $user_information['birthdate'] = $data['birthdate'];
            }
            if (!isset($data['gender'])) {
                $error_array->offsetSet('gender', "The gender field is required.");
                $error_count++;
            } else {
                $user_information['gender'] = $data['gender'];
            }
            if (!isset($data['email'])) {
                $error_array->offsetSet('email', "The email field is required.");
                $error_count++;
            }if (!isset($data['access_id'])) {
                $error_array->offsetSet('access_id', "The access field is required.");
                $error_count++;
            }

            if (isset($data['contact_number'])) {
                $user_information['contact_number'] = $data['contact_number'];
            }
            if (!isset($data['address'])) {
                $error_array->offsetSet('address', "The address field is required.");
                $error_count++;
            } else {
                $user_information['address'] = $data['address'];
            }
            if (isset($data['salary_rate'])) {
                $user_information['salary_rate'] = $data['salary_rate'];
            }
            if (!isset($data['status'])) {
                $error_array->offsetSet('employee_status', "Please define employee status.");
                $error_count++;
            } else {
                $user_information['status'] = $data['status'];
            }
            if (!isset($data['type'])) {
                $error_array->offsetSet('employee_type', "Please define employee status type.");
                $error_count++;
            } else {
                $user_information['type'] = $data['type'];
            }
            if (!isset($data['hired_date'])) {
                $error_array->offsetSet('hired_date', "Hired date must be provided.");
                $error_count++;
            }
            if (isset($data['hired_date'])) {
                $user_information['hired_date'] = $data['hired_date'];
            }
            if (isset($data['separation_date'])) {
                $user_information['separation_date'] = $data['separation_date'];
            }
            if (isset($data['excel_hash'])) {
                $user_information['excel_hash'] = $data['excel_hash'];
            }
            if (isset($data['p_email'])) {
                $user_information['p_email'] = $data['p_email'];
            }
            if (isset($data['status_reason'])) {
                $user_information['status_reason'] = $data['status_reason'];
            }
            if (isset($data['imageName'])) {
                define('UPLOAD_DIR', 'storage/images/');
                $file = request()->image->move(UPLOAD_DIR, $data['imageName']);
                $url = asset($file);
                $user_information['image_url'] = $url;
            }
            if ($error_count > 0) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $error_array,
                    ],
                ]);
            }
            if (isset($data['firstname'], $data['middlename'], $data['lastname'])) {
                $user_information['excel_hash'] = strtolower($data['firstname'] . $data['middlename'] . $data['lastname']);
                $user_informations = $this->user_infos->init($this->user_infos->pullFillable($user_information));
                if (!$user_informations->save($data)) {
                    $url = $user_informations->image_url;
                    $file_name = basename($url);
                    Storage::delete('images/' . $file_name);
                    if (strpos($user_informations->errors(), 'user_infos_excel_hash_unique') !== false) {
                        $error_array->offsetSet('excelhash', "Full Name is already in Use. Please use another Name.");
                        $error_count++;
                    } else {
                        return $this->setResponse([
                            "code" => 500,
                            "title" => "Data Validation Error.",
                            "description" => "An error was detected on one of the inputted data.",
                            "meta" => [
                                "errors" => $user_informations->errors(),
                            ],
                        ]);
                    }
                }
            }

            if ($error_count > 0) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $error_array,
                    ],
                ]);
            }
            $user_id = $user_informations->id;
            $hierarchy['child_id'] = $user_id;
            if (isset($data['parent_id'])) {
                $hierarchy['parent_id'] = $data['parent_id'];
            }
            $user_hierarchy = $this->access_level_hierarchy->init($this->access_level_hierarchy->pullFillable($hierarchy));

            $user_data['uid'] = $user_id;
            if (isset($data['email'])) {
                $user_data['email'] = $data['email'];
            }
            if (isset($data['access_id'])) {
                $user_data['access_id'] = $data['access_id'];
            }
            if (!isset($data['company_id'])) {
                $error_array->offsetSet('company_id', "Company ID must be provided.");
                $error_count++;
            }
            if (isset($data['company_id'])) {
                $user_data['company_id'] = $data['company_id'];
            }
            if (isset($data['contract'])) {
                $user_data['contract'] = $data['contract'];
            }

            // $password_combi = strtolower($data['firstname'] . $data['lastname']);
            // $password_combi = str_replace('ñ','n',$password_combi);
            // $password = trim(preg_replace('/[^A-Za-z0-9-]/', '', $password_combi)," ");
            $password = "123456";
            // dd($password);
            $user_data['password'] = bcrypt($password);
            $users_data = $this->user_datum->init($this->user_datum->pullFillable($user_data));
            $users_data;
            if (isset($data['status'])) {
                $status_logs['user_id'] = $user_id;
                $status_logs['status'] = $data['status'];
            }
            if (isset($data['type'])) {
                $status_logs['type'] = $data['type'];
            }
            if (isset($data['status_reason'])) {
                $status_logs['reason'] = $data['status_reason'];
            }
            if (isset($data['hired_date'])) {
                $status_logs['hired_date'] = $data['hired_date'];
            }
            if (isset($data['separation_date'])) {
                $status_logs['separation_date'] = $data['separation_date'];
            }
            $status = $this->user_status->init($this->user_status->pullFillable($status_logs));
            $action = "Created";
            if (!$status->save($data)) {
                $user_info_delete = $this->user_infos->find($user_id);
                $user_info_delete->forceDelete();
                $url = $user_info_delete->image_url;
                $file_name = basename($url);
                Storage::delete('images/' . $file_name);
                $error_array->offsetSet('status_save', "Saving Error on Status");
                $error_count++;
            }
            if (!$user_hierarchy->save($data)) {
                $user_info_delete = $this->user_infos->find($user_id);
                $user_info_delete->forceDelete();
                $url = $user_info_delete->image_url;
                $file_name = basename($url);
                Storage::delete('images/' . $file_name);
                $error_array->offsetSet('user_hierarchy_error', "Saving Error on User Hierarchy");
                $error_count++;
            }
            // hierarchy log insertion
            $hierarchy_log = $this->hierarchy_log;
            $hierarchy_log->child_id = $user_id;
            $hierarchy_log->parent_id = $data["parent_id"];
            $hierarchy_log->start_date = Carbon::parse($data["hired_date"])->startOfDay()->toDateTimeString();
            $hierarchy_log->save();

            if (!$users_data->save($data)) {
                $user_id;
                $user_info_delete = $this->user_infos->find($user_id);
                $user_info_delete->forceDelete();
                $url = $user_info_delete->image_url;
                $file_name = basename($url);
                Storage::delete('images/' . $file_name);
                if (strpos($users_data->errors(), 'users_email_unique') !== false) {
                    $error_array->offsetSet('duplicate_email', "Email is already in use. Please use another valid email.");
                    $error_count++;
                }

            }
            $benefits = [];
            $ben = [];
            $array = json_decode($data['benefits'], true);
            if ($array == []) {
                for ($i = 1; $i < 5; $i++) {
                    $ben['benefit_id'] = $i;
                    $ben['id_number'] = null;
                    $ben['user_info_id'] = $user_id;
                    $user_ben = $this->user_benefits->init($this->user_benefits->pullFillable($ben));
                    array_push($benefits, $user_ben);
                    $user_ben->save();
                }
            } else {
                foreach ($array as $key => $value) {

                    $ben['benefit_id'] = $key + 1;
                    if ($array[$key] == "") {
                        $ben['id_number'] = null;
                    } else {
                        $ben['id_number'] = $array[$key];
                    }
                    $ben['user_info_id'] = $user_id;
                    $user_ben = $this->user_benefits->init($this->user_benefits->pullFillable($ben));
                    array_push($benefits, $user_ben);
                    $user_ben->save();
                }

            }

            if ($error_count > 0) {
                $user_info_delete = $this->user_infos->find($user_id);
                if ($user_info_delete) {
                    $user_info_delete->forceDelete();
                    $url = $user_info_delete->image_url;
                    $file_name = basename($url);
                    Storage::delete('images/' . $file_name);
                }
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $error_array,
                    ],
                ]);
            }
            $logged_data = [
                "user_id" => $auth->id,
                "action" => "Create",
                "affected_data" => $auth->full_name . "[" . $auth->access->name . "] Added a User [" . $user_informations->full_name . "]",
            ];
            $this->logs->logsInputCheck($logged_data);

            return $this->setResponse([
                "code" => 200,
                "title" => "Successfully " . $action . " a User.",
                "meta" => [
                    "user_information" => $user_informations,
                    "user" => $users_data,
                    "benefits" => $benefits,
                    "logs" => $logged_data,
                ],
            ]);
        } else if (isset($data['id'])) {
            // $data['single'] = false;
            // $data['where']  = [
            //     [
            //         "target"   => "user_info_id",
            //         "operator" => "=",
            //         "value"    => "66",
            //     ],
            // ];
            // $user_ben =$this->fetchGeneric($data, $this->user_benefits);
            // $array=json_decode($data['benefits'], true );
            // $ben=[];
            // foreach($user_ben as $key => $value ){
            //     $user_bene = $this->benefit_update->find($value->id);
            //     if($array[$key]==""){
            //         $user_bene['id_number']=NULL;
            //     }else{
            //         $user_bene['id_number']=$array[$key];
            //     }

            //     $user_bene->save();
            //     array_push($ben,$user_bene);
            // }
            // return $this->setResponse([
            //     "code"        => 500,
            //     "title"       => "Data Validation Error.",
            //     "description" => "An error was detected on one of the inputted data.",
            //     "meta"        => [
            //         "errors" => $ben,
            //     ],
            // ]);

            $user_information = $this->user_infos->find($data['id']);
            if ($user_information) {
                // hierarchy log insertion
                $hierarchy_log_id = $this->hierarchy_log->where('child_id', $data["id"])->where('start_date', Carbon::parse($user_information->hired_date)->startOfDay()->toDateTimeString())->first()->id;
                if ($hierarchy_log_id) {
                    $hierarchy_log = $this->hierarchy_log->find($hierarchy_log_id);
                    $hierarchy_log->parent_id = $data["parent_id"];
                    $hierarchy_log->start_date = Carbon::parse($data["hired_date"])->startOfDay()->toDateTimeString();
                    $hierarchy_log->save();
                }
                if (!isset($data['firstname'])) {
                    $error_array->offsetSet('firstname', "The username field is required.");
                    $error_count++;
                } else {
                    $user_information['firstname'] = $data['firstname'];
                }
                if (!isset($data['middlename'])) {
                    $error_array->offsetSet('middlename', "The middlename field is required.");
                    $error_count++;
                } else {
                    $user_information['middlename'] = $data['middlename'];
                }
                if (!isset($data['lastname'])) {
                    $error_array->offsetSet('lastname', "The lastname field is required.");
                    $error_count++;
                } else {
                    $user_information['lastname'] = $data['lastname'];
                }
                $user_information['excel_hash'] = strtolower($data['firstname'] . $data['middlename'] . $data['lastname']);
                if (!isset($data['birthdate'])) {
                    $error_array->offsetSet('birthdate', "The birthdate field is required.");
                    $error_count++;
                } else {
                    $user_information['birthdate'] = $data['birthdate'];
                }
                if (!isset($data['gender'])) {
                    $error_array->offsetSet('gender', "The gender field is required.");
                    $error_count++;
                } else {
                    $user_information['gender'] = $data['gender'];
                }
                if (!isset($data['email'])) {
                    $error_array->offsetSet('email', "The email field is required.");
                    $error_count++;
                }if (!isset($data['access_id'])) {
                    $error_array->offsetSet('access_id', "The access field is required.");
                    $error_count++;
                }

                if (isset($data['contact_number'])) {
                    $user_information['contact_number'] = $data['contact_number'];
                }
                if (!isset($data['address'])) {
                    $error_array->offsetSet('address', "The address field is required.");
                    $error_count++;
                } else {
                    $user_information['address'] = $data['address'];
                }
                if (isset($data['salary_rate'])) {
                    $user_information['salary_rate'] = $data['salary_rate'];
                }
                if (!isset($data['status'])) {
                    $error_array->offsetSet('status', "Please define employee status.");
                    $error_count++;
                } else {
                    $user_information['status'] = $data['status'];
                }
                if (!isset($data['type'])) {
                    $error_array->offsetSet('type', "Please define employee status type.");
                    $error_count++;
                } else {
                    $user_information['type'] = $data['type'];
                }
                if (!isset($data['hired_date'])) {
                    $error_array->offsetSet('hired_date', "Hired date must be provided.");
                    $error_count++;
                }
                if (isset($data['hired_date'])) {
                    $user_information['hired_date'] = $data['hired_date'];
                }
                if (isset($data['separation_date'])) {
                    $user_information['separation_date'] = $data['separation_date'];
                }
                if (isset($data['p_email'])) {
                    $user_information['p_email'] = $data['p_email'];
                }
                if (isset($data['status_reason'])) {
                    $user_information['status_reason'] = $data['status_reason'];
                }
                if (isset($data['imageName'])) {
                    if ($user_information->image_url == null) {
                        define('UPLOAD_DIR', 'storage/images/');
                        $file = request()->image->move(UPLOAD_DIR, $data['imageName']);
                        $url = asset($file);
                        $user_information['image_url'] = $url;
                    } else {
                        $url = $user_information->image_url;
                        $file_name = basename($url);
                        Storage::delete('images/' . $file_name);
                        define('UPLOAD_DIR', 'storage/images/');
                        $file = request()->image->move(UPLOAD_DIR, $data['imageName']);
                        $url = asset($file);
                        $user_information['image_url'] = $url;
                    }

                }
                $user_data = $this->user_data_update->find($data['id']);
                if (isset($data['email'])) {
                    $user_data['email'] = $data['email'];
                }
                if (isset($data['access_id'])) {
                    $user_data['access_id'] = $data['access_id'];
                }
                if (isset($data['company_id'])) {
                    $user_data['company_id'] = $data['company_id'];
                }
                if (isset($data['contract'])) {
                    $user_data['contract'] = $data['contract'];
                }
                $hierarchy = $this->hierarchy_update->find($data['id']);
                if (isset($data['parent_id'])) {
                    $hierarchy['parent_id'] = $data['parent_id'];
                }
                if (!$user_information->save($data)) {
                    if (strpos($user_information->errors(), 'user_infos_excel_hash_unique') !== false) {
                        $error_array->offsetSet('excelhash', "Full Name is already in Use. Please use another Name.");
                        $error_count++;
                    }
                }
                if (!$user_data->save($data)) {
                    $user_info_delete = $this->user_infos->find($data['id']);
                    $user_info_delete->forceDelete();
                    $url = $user_info_delete->image_url;
                    $file_name = basename($url);
                    Storage::delete('images/' . $file_name);
                    if (strpos($users_data->errors(), 'users_email_unique') !== false) {
                        $error_array->offsetSet('duplicate_email', "Email is already in use. Please use another valid email.");
                        $error_count++;
                    }
                }
                if (!$hierarchy->save($data)) {
                    $user_info_delete = $this->user_infos->find($data['id']);
                    $user_info_delete->forceDelete();
                    $url = $user_info_delete->image_url;
                    $file_name = basename($url);
                    Storage::delete('images/' . $file_name);
                    $error_array->offsetSet('user_hierarchy_error', "Saving Error on User Hierarchy");
                    $error_count++;
                }
                if ($error_count > 0) {
                    return $this->setResponse([
                        "code" => 500,
                        "title" => "Data Validation Error.",
                        "description" => "An error was detected on one of the inputted data.",
                        "meta" => [
                            "errors" => $error_array,
                        ],
                    ]);
                }
                $ben = [];
                if (isset($data['benefits'])) {

                    $data['single'] = false;
                    $data['where'] = [
                        [
                            "target" => "user_info_id",
                            "operator" => "=",
                            "value" => $data['id'],
                        ],
                    ];
                    $user_ben = $this->fetchGeneric($data, $this->user_benefits);
                    $array = json_decode($data['benefits'], true);

                    foreach ($user_ben as $key => $value) {
                        $user_bene = $this->benefit_update->find($value->id);
                        if ($array[$key] == "") {
                            $user_bene['id_number'] = null;
                        } else {
                            $user_bene['id_number'] = $array[$key];
                        }

                        $user_bene->save();
                        array_push($ben, $user_bene);
                    }

                }
                $action = "Updated";
                $logged_data = [
                    "user_id" => $auth->id,
                    "action" => "Create",
                    "affected_data" => $auth->full_name . "[" . $auth->access->name . "] Updated a User [" . $user_information->full_name . "]",
                ];
                $this->logs->logsInputCheck($logged_data);

                // update hierarchy log
                if (strtolower($data['status']) == 'inactive' &&
                    (strtolower($data['type']) == 'terminated' ||
                        strtolower($data['type']) == 'resigned')) {

                    $user_information->child_logs()->whereNull('end_date')->update(['end_date' => Carbon::now()]);
                    $user_information->parent_logs()->whereNull('end_date')->update(['end_date' => Carbon::now()]);
                }

                return $this->setResponse([
                    "code" => 200,
                    "title" => "Successfully " . $action . " a User.",
                    "meta" => [
                        "user_information" => $user_information,
                        "user" => $user_data,
                        "benefits" => $ben,
                        "hierarchy" => $hierarchy,
                        "logs" => $logged_data,
                    ],
                ]);
            } else {
                return $this->setResponse([
                    'code' => 500,
                    'title' => 'User Not Found.',
                ]);

            }
        }
    }

    public function updateUser($data = [])
    {
        if (!isset($data['password'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "password is not set.",
            ]);
        }

        $user_information = $this->user_data_update->find($data['id']);
        if ($user_information) {
            // $hashPass=bcrypt($data['password']);
            $user_information->password = $data['password'];
            $user_information['loginFlag'] = 1;
            if (!$user_information->save($data)) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error on User.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $Users->errors(),
                    ],
                ]);
            } else {
                $action = "Updated";
                return $this->setResponse([
                    "code" => 200,
                    "title" => "Successfully " . $action . " a User.",
                    "meta" => [
                        "user_information" => $user_information,
                    ],
                ]);
            }

        } else {
            return $this->setResponse([
                'code' => 500,
                'title' => 'User Not Found.',
            ]);

        }

    }

    public function resetPass($data = [])
    {
        if (!isset($data['id'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "id is not set.",
            ]);
        }

        $user_information = $this->user->where('uid', $data['id'])->first();
        if ($user_information) {
            // $hashPass=bcrypt($data['password']);
            $user_information['password'] = $data['password'];
            $user_information['loginFlag'] = 0;
            $user_information['password_updated'] = Carbon::now()->toDateTimeString();
            if (!$user_information->save($data)) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error on User.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $Users->errors(),
                    ],
                ]);
            } else {
                $action = "Updated";
                return $this->setResponse([
                    "code" => 200,
                    "title" => "Successfully " . $action . " a User.",
                    "meta" => [
                        "password" => $user_information->password,
                        "user_information" => $user_information,
                    ],
                ]);
            }

        } else {
            return $this->setResponse([
                'code' => 500,
                'title' => 'User Not Found.',
            ]);

        }

    }

    public function updateStatus($data = [])
    {
        // data validation
        $action = null;
        $auth_id = auth()->user()->id;
        $auth = $this->user->find($auth_id);

        if (!isset($data['status'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "status is not set.",
            ]);
        }
        if (!isset($data['user_id'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "user id is not set.",
            ]);
        }
        if (!isset($data['reason'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "reason is not set.",
            ]);
        }
        if (!isset($data['type'])) {
            return $this->setResponse([
                'code' => 500,
                'title' => "type is not set.",
            ]);
        }

        $status = $this->user_status->init($this->user_status->pullFillable($data));
        $Users = $this->user_infos->find($data['user_id']);
        $Users->status = $data['status'];
        $Users->status_reason = $data['reason'];
        $Users->type = $data['type'];
        if (isset($data['hired_date'])) {
            $Users->hired_date = $data['hired_date'];
        }
        if (isset($data['separation_date'])) {
            $Users->separation_date = $data['separation_date'];
        }
        $action = "Updated";
        if (!$Users->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error on User.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $Users->errors(),
                ],
            ]);
        }
        if (!$status->save($data)) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Data Validation Error.",
                "description" => "An error was detected on one of the inputted data.",
                "meta" => [
                    "errors" => $status->errors(),
                ],
            ]);
        }
        $logged_data = [
            "user_id" => $auth->id,
            "action" => "Update",
            "affected_data" => $auth->full_name . "[" . $auth->access->name . "] Update a User [" . $user_informations->full_name . "] Status.",
        ];
        $this->logs->logsInputCheck($logged_data);
        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully " . $action . " a User Status.",
            "meta" => [
                "Users" => $Users,
                "Status Log" => $status,
                "logs" => $logged_data,
            ],
        ]);

    }

    public function bulkUpdateStatus($data = [])
    {
        // data validation
        $action = null;
        $array = $data['user_id'];
        $auth_id = auth()->user()->id;
        $auth = $this->user->find($auth_id);
        $all_users = [];
        foreach ($array as $key => $value) {
            $data['user_id'] = $value;
            if (!isset($data['status'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "status is not set.",
                ]);
            }
            if (!isset($data['user_id'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "user id is not set.",
                ]);
            }
            if (!isset($data['type'])) {
                return $this->setResponse([
                    'code' => 500,
                    'title' => "type is not set.",
                ]);
            }

            $status = $this->user_status->init($this->user_status->pullFillable($data));
            $Users = $this->user_infos->find($value);
            $Users->status = $data['status'];
            $Users->status_reason = $data['reason'];
            if (isset($data['hired_date'])) {
                $Users->hired_date = $data['hired_date'];
            }
            if (isset($data['separation_date'])) {
                $Users->separation_date = $data['separation_date'];
            }
            $action = "Updated";
            if (!$Users->save($data)) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error on User.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $Users->errors(),
                    ],
                ]);
            }
            if (!$status->save($data)) {
                return $this->setResponse([
                    "code" => 500,
                    "title" => "Data Validation Error.",
                    "description" => "An error was detected on one of the inputted data.",
                    "meta" => [
                        "errors" => $status->errors(),
                    ],
                ]);
            }

            if (strtolower($data['status']) == 'inactive' &&
                (strtolower($data['type']) == 'terminated' ||
                    strtolower($data['type']) == 'resigned')) {

                $Users->child_logs()->whereNull('end_date')->update(['end_date' => $data['separation_date']]);
                $Users->parent_logs()->whereNull('end_date')->update(['end_date' => $data['separation_date']]);
            } else if (strtolower($data['status']) == 'active') {
                if (!$Users->parent_logs()->whereNull('end_date')->first()) {
                    $this->hierarchy_log->save([
                        'parent_id' => $this->access_level_hierarchy->where('child_id', $Users->id)->first()->parent_id,
                        'child_id' => $Users->id,
                        'start_date' => $data['hired_date'],
                    ]);
                }
            }

            array_push($all_users, $Users);
        }
        $logged_data = [
            "user_id" => $auth->id,
            "action" => "Update",
            "affected_data" => $auth->full_name . "[" . $auth->access->name . "]  Updated a Users Status.",
        ];
        $this->logs->logsInputCheck($logged_data);
        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully " . $action . " a Users Status.",
            "meta" => [
                "Users" => $all_users,
                "logs" => $logged_data,
            ],
        ]);

    }

    public function search($data)
    {
        if (!isset($data['query'])) {
            return $this->setResponse([
                "code" => 500,
                "title" => "Query is not set",
                "parameters" => $data,
            ]);
        }

        $result = $this->user_info;
        $meta_index = "users";
        $parameters = [
            "query" => $data['query'],
        ];

        $data['relations'] = ["user_info", "accesslevel", "benefits"];

        $data['where'] = [
            [
                "target" => "excel_hash",
                "operator" => "!=",
                "value" => 'development',
            ],
        ];

        if (isset($data['target'])) {
            foreach ((array) $data['target'] as $index => $column) {
                if (str_contains($column, "full_name")) {
                    $data['target'][] = 'firstname';
                    $data['target'][] = 'middlename';
                    $data['target'][] = 'lastname';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "gender")) {
                    $data['target'][] = 'gender';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "position")) {
                    $access = AccessLevel::all();
                    $access_id = [];
                    foreach ($access as $key => $value) {
                        if (strpos(strtolower(str_replace(' ', '', $value->code)), strtolower(str_replace(' ', '', $data['query']))) !== false) {
                            array_push($access_id, $value->id);
                            // $countni++;
                        }

                    }
                    $data['wherehas'][] = [
                        'relation' => 'user_info',
                        'target' => [
                            [
                                'column' => 'access_id',
                                'operator' => 'wherein',
                                'value' => $access_id,
                            ],

                        ],
                    ];
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "access_id")) {
                    $array = [];
                    $countresult = 0;
                    $data['target'][] = 'user_info.access_id';
                    unset($data['target'][$index]);
                    $results = $this->genericSearch($data, $result)->get()->all();
                    foreach ($results as $key => $value) {
                        if ($value->access_id == $data['query']) {
                            array_push($array, $value);
                            $countresult += 1;
                        }
                    }
                    if ($array != []) {
                        return $this->setResponse([
                            "code" => 200,
                            "title" => "Successfully searched Users",
                            "meta" => [
                                $meta_index => $array,
                                "count" => $countresult,
                            ],
                            "parameters" => $parameters,
                        ]);
                    } else {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "No user are found",
                            "meta" => [
                                $meta_index => $array,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }

                }
                if (str_contains($column, "p_email")) {
                    $data['target'][] = 'p_email';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "email")) {
                    $data['target'][] = 'user_info.email';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "status")) {
                    $data['target'][] = 'status';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "type")) {
                    $data['target'][] = 'type';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "address")) {
                    $data['target'][] = 'address';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "birthdate")) {
                    $data['target'][] = 'birthdate';
                    unset($data['target'][$index]);
                }
                if (str_contains($column, "hired_date")) {
                    $count = 0;
                    $array = json_decode($data['query'], true);
                    $general = [];
                    $start = new DateTime($array[0]);
                    $end = (new DateTime($array[1]))->modify('+1 day');
                    $interval = new DateInterval('P1D');
                    $period = new DatePeriod($start, $interval, $end);
                    foreach ($period as $dt) {
                        // array_push($date,$dt->format("Y-m-d"));
                        $data['query'] = $dt->format("Y-m-d");
                        $data['target'][] = 'hired_date';
                        unset($data['target'][$index]);

                        // $count_data = $data;
                        $results = $this->genericSearch($data, $result)->get()->all();
                        if ($results) {
                            array_push($general, $results);
                            $count += 1;
                        }

                    }
                    $new = [];
                    while ($item = array_shift($general)) {
                        array_push($new, ...$item);
                    }
                    if ($result == null) {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "No user are found",
                            "meta" => [
                                $meta_index => $new,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }

                    $count_data['search'] = true;
                    // $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

                    return $this->setResponse([
                        "code" => 200,
                        "title" => "Successfully searched Users",
                        "meta" => [
                            $meta_index => $new,
                            "count" => $count,
                        ],
                        "parameters" => $parameters,
                    ]);
                }
                if (str_contains($column, "separation_date")) {
                    $count = 0;
                    $array = json_decode($data['query'], true);
                    $general = [];
                    $start = new DateTime($array[0]);
                    $end = (new DateTime($array[1]))->modify('+1 day');
                    $interval = new DateInterval('P1D');
                    $period = new DatePeriod($start, $interval, $end);
                    foreach ($period as $dt) {
                        // array_push($date,$dt->format("Y-m-d"));
                        $data['query'] = $dt->format("Y-m-d");
                        $data['target'][] = 'separation_date';
                        unset($data['target'][$index]);

                        // $count_data = $data;
                        $results = $this->genericSearch($data, $result)->get()->all();
                        if ($results) {
                            array_push($general, $results);
                            $count += 1;
                        }

                    }
                    $new = [];
                    while ($item = array_shift($general)) {
                        array_push($new, ...$item);
                    }
                    if ($result == null) {
                        return $this->setResponse([
                            'code' => 404,
                            'title' => "No user are found",
                            "meta" => [
                                $meta_index => $new,
                            ],
                            "parameters" => $parameters,
                        ]);
                    }

                    $count_data['search'] = true;
                    // $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

                    return $this->setResponse([
                        "code" => 200,
                        "title" => "Successfully searched Users",
                        "meta" => [
                            $meta_index => $new,
                            "count" => $count,
                        ],
                        "parameters" => $parameters,
                    ]);
                }
            }
        }

        $count_data = $data;
        $result = $this->genericSearch($data, $result)->get()->all();

        if ($result == null) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No user are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count_data['search'] = true;
        $count = $this->countData($count_data, refresh_model($this->user_info->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully searched Users",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }

    public function fetchUserLog($data = [])
    {
        $meta_index = "User";
        $parameters = [];
        $count = 0;

        if (isset($data['id']) &&
            is_numeric($data['id'])) {

            $meta_index = "User";
            $data['single'] = false;
            $data['where'] = [
                [
                    "target" => "id",
                    "operator" => "=",
                    "value" => $data['id'],
                ],
            ];

            $parameters['user_id'] = $data['id'];

        }

        $count_data = $data;

        $data['relations'] = ["user_info", "user_logs", "accesslevel"];

        $result = $this->fetchGeneric($data, $this->user);

        if (!$result) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No agent logs are found",
                "meta" => [
                    $meta_index => $result,
                ],
                "parameters" => $parameters,
            ]);
        }

        $count = $this->countData($count_data, refresh_model($this->user->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved agent logs",
            "meta" => [
                $meta_index => $result,
                "count" => $count,
            ],
            "parameters" => $parameters,
        ]);
    }
    public function getCluster($data = [])
    {
        $meta_index = "options";
        $parameters = [];
        $count = 0;

        $count_data = $data;
        $data['relations'] = ["accesslevel", "accesslevelhierarchy"];
        $result = $this->fetchGeneric($data, $this->select_users);
        $results = [];
        $keys = 0;
        $parent = null;
        foreach ($result as $key => $value) {
            if ($value->accesslevelhierarchy->child_id == $data['id']) {
                $parent = $value->accesslevelhierarchy->parent_id;
                array_push($results, $value);
                foreach ($result as $key => $val) {
                    $last_child2 = null;
                    if ($val->accesslevelhierarchy->child_id == $parent) {
                        $keys++;
                        $count++;
                        array_push($results, $val);

                        foreach ($result as $key => $vals) {
                            if ($vals->accesslevelhierarchy->parent_id == $parent && $vals->accesslevelhierarchy->child_id != $data['id']) {
                                $keys++;
                                $count++;
                                array_push($results, $vals);
                            }
                            $last_child2 = $val->accesslevelhierarchy->parent_id;
                            if ($vals->accesslevelhierarchy->child_id == $last_child2) {
                                $keys++;
                                $count++;
                                array_push($results, $vals);

                            }

                        }

                    }

                }

                $keys++;
                $count++;
            }

        }

        if (!$results) {
            return $this->setResponse([
                'code' => 404,
                'title' => "No users found",
                "meta" => [
                    $meta_index => $results,
                ],
                "parameters" => $parameters,
            ]);
        }

        // $count = $this->countData($count_data, refresh_model($this->users->getModel()));

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully retrieved Users Cluster",
            "description" => "Cluster",
            "meta" => [
                $meta_index => $results,
                "count" => $count,
            ],

        ]);
    }

    public function remote($data = [])
    {
        /**
         * static queries
         * status = active
         * relations = user_info
         * dept_heads or department heads
         */
        $dept_heads = [
            "admin" => [1, 2],
            "it" => [4], // information tech
            "qa" => [8, 10], // quality assurance
            "rta" => [12, 13], // real time analyst
            "op" => [15, 16], // operations
            "fo" => [19], // finance officer
            "all" => [1, 2, 4, 8, 10, 12, 13, 15, 16, 19],
        ];

        $data["relations"][] = "user_info";

        if (isset($data["status"])) {
            $data["where"][] = [
                "target" => "status",
                "operator" => "=",
                "value" => "active",
            ];
        }

        // list value mandatory heads/subordinates
        if (!isset($data["list"])) {
            return $this->setResponse([
                'code' => 422,
                'title' => "list parameter value is required.",
            ]);
        }

        if ($data["list"] == "heads") {
            if (isset($data["position_id"]) && isset($data["department"])) {
                return $this->setResponse([
                    'code' => 422,
                    'title' => "presence of position id and department parameters cannot be processed.",
                ]);
            }

            if (isset($data["position_id"])) {
                // to be resumed get head list by position id
                $data["wherehas"][] = [
                    "relation" => "user_info",
                    "target" => [
                        [
                            "column" => "access_id",
                            "operator" => "=",
                            "value" => $data["position_id"],
                        ],
                    ],
                ];
            }

            if (isset($data["department"])) {
                $data["wherehas"][] = [
                    "relation" => "user_info",
                    "target" => [
                        [
                            "column" => "access_id",
                            "operator" => "wherein",
                            "value" => $dept_heads[$data["department"]],
                        ],
                    ],
                ];
            }
        } else {
            // list == subordinates
            if (isset($data["head_id"])) {
                $head_access = $this->user->where("uid", $data["head_id"])->first()->access_id;
                $children_access = collect($this->access_level
                        ->where("parent", $head_access)
                        ->get())->pluck("id")->all();

                // dd($children_access);

                $data["wherehas"][] = [
                    "relation" => "user_info",
                    "target" => [
                        [
                            "column" => "access_id",
                            "operator" => "wherein",
                            "value" => $children_access,
                        ],
                    ],
                ];
            }

        }

        $result = $this->fetchGeneric($data, $this->user_info);

        $result = collect($result)->filter(function ($i) {
            return $i->id != 3;
        });

        if (isset($data["full_name"])) {
            $result = collect($result)->filter(function ($i) use ($data) {
                if (strpos(strtolower($i['full_name']), strtolower($data["full_name"])) !== false) {
                    return $i;
                }
            });
            $result = array_values($result->toArray());
        }

        return $this->setResponse([
            "code" => 200,
            "title" => "Successfully query.",
            "meta" => [
                "remote" => $result,
                "count" => collect($result)->count(),
            ],
            "parameters" => $data,
        ]);
    }

}
