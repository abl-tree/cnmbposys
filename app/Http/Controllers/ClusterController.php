<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 12/05/2019
 * Time: 11:06 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\ClusterRepository;

class ClusterController extends BaseController
{
    protected $cluster_repo;

    public function __construct(
        ClusterRepository $clusterRepository
    ){
        $this->cluster_repo = $clusterRepository;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->cluster_repo->defineCluster($data))->json();
    }
}