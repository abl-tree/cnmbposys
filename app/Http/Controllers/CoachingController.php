<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Data\Repositories\CoachingRepository;
class CoachingController extends BaseController
{
    protected $coaching;
    public function __construct(
        CoachingRepository $coachingRepository
    ) {
        $this->coaching = $coachingRepository;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        if (isset($data['image'])) {
            request()->validate([

                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',

            ]);
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            $data['imageName'] = $imageName;
        }

        return $this->absorb($this->coaching->create($data))->json();
    }

    public function verifyCoach(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->coaching->verifyCoach($data))->json();
    }
    public function agentAction(Request $request)
    {
        $data = $request->all();
        return $this->absorb($this->coaching->agentAction($data))->json();
    }
    public function coachDetails(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        if (!isset($data['id']) ||
            !is_numeric($data['id']) ||
            $data['id'] <= 0) {
            return $this->setResponse([
                'code' => 500,
                'title' => "Coach ID is invalid.",
            ]);
        }

        return $this->absorb($this->coaching->coachDetails($data))->json();
    }
}
