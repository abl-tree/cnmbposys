<?php

namespace App\Http\Controllers;

use App\Data\Repositories\ClusterRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ClusterController extends BaseController
{
    protected $cluster_repo;

    public function __construct(
        ClusterRepository $clusterRepository
    ) {
        $this->cluster_repo = $clusterRepository;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->cluster_repo->defineCluster($data))->json();
    }

    public function update(Request $request, $cluster_id)
    {
        $data = $request->all();
        return $this->absorb($this->cluster_repo->defineCluster($data))->json();
    }
}
