<?php

namespace App\Http\Controllers;

use App\Data\Repositories\ReportsRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ReportsController extends BaseController
{
    protected $user_reports;

    public function __construct(
        ReportsRepository $ReportsRepository
    ) {
        $this->user_reports = $ReportsRepository;
    }
    public function index(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getAllReports($data))->json();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $data = $request->all();
        //for notification
        $data['endpoint'] = $request->route()->uri;
        return $this->absorb($this->user_reports->ReportsInputCheck($data))->json();
    }

    public function addSanctionType(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->addSanctionType($data))->json();
    }

    public function addSanctionLevel(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->addSanctionLevel($data))->json();
    }
    public function userResponse(Request $request)
    {
        $data = $request->all();
        //for notification
        $data['endpoint'] = $request->route()->uri;
        return $this->absorb($this->user_reports->userResponse($data))->json();
    }

    public function getSanctionType(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getSanctionType($data))->json();
    }
    public function getSanctionLevel(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getSanctionLevel($data))->json();
    }
    public function getAllUser(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getAllUser($data))->json();
    }
    public function getSanctionTypes(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getSanctionTypes($data))->json();
    }
    public function getSanctionTypesSearch(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getSanctionTypesSearch($data))->json();
    }
    public function getSanctionLevels(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getSanctionLevels($data))->json();
    }
    public function getSanctionLevelsSearch(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->user_reports->getSanctionLevelsSearch($data))->json();
    }

    public function getAll_Ir(Request $request)
    {

        $data = $request->all();
        return $this->absorb($this->user_reports->getAll_Ir($data))->json();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request, $id)
    {
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is invalid.",
            ]);
        }

        return $this->absorb($this->user_reports->fetchUserReport($data))->json();
    }

    public function userFiledIR(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is invalid.",
            ]);
        }

        return $this->absorb($this->user_reports->userFiledIR($data))->json();
    }
    public function getAllUserUnder(Request $request, $id)
    {
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is invalid.",
            ]);
        }

        return $this->absorb($this->user_reports->getAllUserUnder($data))->json();
    }

    public function getSelectAllUserUnder(Request $request, $id)
    {
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "User ID is invalid.",
            ]);
        }

        return $this->absorb($this->user_reports->getSelectAllUserUnder($data))->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        //for notification
        $data['endpoint'] = $request->route()->uri;

        return $this->absorb($this->user_reports->ReportsInputCheck($data))->json();
    }
    public function update_response(Request $request, $id)
    {
        $data = $request->all();
        $data['user_response_id'] = $id;
        //for notification
        $data['endpoint'] = $request->route()->uri;

        return $this->absorb($this->user_reports->userResponse($data))->json();
    }
    public function update_stype(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        return $this->absorb($this->user_reports->addSanctionType($data))->json();
    }
    public function update_slevel(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        return $this->absorb($this->user_reports->addSanctionLevel($data))->json();
    }
    public function delete(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        //for notification
        $data['endpoint'] = $request->route()->uri;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "IR ID is not set.",
            ]);
        }

        return $this->absorb($this->user_reports->deleteReport($data))->json();
    }
    public function delete_response(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "IR ID is not set.",
            ]);
        }

        return $this->absorb($this->user_reports->deleteResponse($data))->json();
    }

    public function delete_stype(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Sanction Type ID is not set.",
            ]);
        }

        return $this->absorb($this->user_reports->deleteStype($data))->json();
    }
    public function delete_slevel(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Sanction Level ID is not set.",
            ]);
        }

        return $this->absorb($this->user_reports->deleteSlevel($data))->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
