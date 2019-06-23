<?php

namespace App\Http\Controllers;

use App\Data\Repositories\AccessLevelHierarchyRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class AccessLevelHierarchyController extends BaseController
{
    protected $access_level_repo;

    public function __construct(
        AccessLevelHierarchyRepository $accessLevelRepository
    ) {
        $this->access_level_repo = $accessLevelRepository;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->access_level_repo->defineAccessLevelHierarchy($data))->json();
    }

    public function getUnderPosition()
    {

    }
}
