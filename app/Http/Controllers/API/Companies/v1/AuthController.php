<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Http\Controllers\Controller;
use App\Models\Companies\v1\Users;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $params = $request->all();

        return Users::generateToken($params, $request->method(), $request);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return Users::getById($user->id);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logout success'
        ]);

    }
}
