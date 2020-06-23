<?php

namespace App\Http\Controllers;

use App\Data\Repositories\ChartsRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ChartsController extends BaseController
{
    protected $charts_repo;

    public function __construct(
        ChartsRepository $chartsRepository
    ) {
        $this->charts_repo = $chartsRepository;
    }

    public function dailyAgentScheduleStats(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->charts_repo->dailyScheduleStats($data))->json();
    }

}
