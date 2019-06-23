<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class TokenController
 * @package App\Http\Controllers\Auth
 *
 */
class TokenController extends BaseController
{

    /**
     * Fetch user information together with the access_token
     *
     * @param Request $request
     * @return mixed
     */
    public function getUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'code' => 500,
                'title' => 'User not found',
                'meta' => [
                    'user' => $user,
                ],
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'title' => 'Successfully retrieved user',
            'meta' => [
                'user' => $user,
            ],
        ]);
    }

    /**
     * Invalidate or revoke token.
     *
     * @param Request $request
     * @return mixed
     */
    public function revokeToken(Request $request)
    {
        $token = Auth::user()->token()->revoke();

        if (!$token) {
            return response()->json([
                'code' => 500,
                'title' => 'Token not found',
                'meta' => [
                    'token' => $token,
                ],
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'title' => 'Successfully revoked token',
            'meta' => [
                'token' => $token,
            ],
        ]);
    }
}
