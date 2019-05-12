<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 12/05/2019
 * Time: 10:57 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\AccessLevelHierarchyRepository;

class AccessLevelHierarchyController extends BaseController
{
    protected $access_level_repo;

    public function __construct(
        AccessLevelHierarchyRepository $accessLevelRepository
    ){
        $this->access_level_repo = $accessLevelRepository;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->access_level_repo->defineAccessLevelHierarchy($data))->json();
    }
}