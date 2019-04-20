<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 10/04/2019
 * Time: 9:41 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\AttendanceRepository;


class AttendanceController extends BaseController
{
    protected $attendance_repo;

    public function __construct(
        AttendanceRepository $attendanceRepository
    ){
        $this->attendance_repo = $attendanceRepository;
    }

    public function all(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->attendance_repo->fetchAgentAttendance($data))->json();
    }

    public function bulkScheduleInsertion(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->attendance_repo->bulkScheduleInsertion($data))->json();
    }

    public function create(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->attendance_repo->defineAgentAttendance($data))->json();
    }

    public function delete(Request $request, $id)
    {
        $data       = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code'  => 500,
                'title' => "Schedule ID is not set.",
            ]);
        }

        return $this->absorb($this->attendance_repo->deleteAgentAttendance($data))->json();
    }

    public function excelData(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->attendance_repo->excelData($data))->json();
    }

    public function fetch(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code'  => 500,
                'title' => "Attendance ID is invalid.",
            ]);
        }

        return $this->absorb($this->attendance_repo->fetchAgentAttendance($data))->json();
    }

    public function search(Request $request)
    {
        $data = $request->all();

        return $this->absorb($this->attendance_repo->searchAgentAttendance($data))->json();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        return $this->absorb($this->attendance_repo->defineAgentAttendance($data))->json();
    }
}