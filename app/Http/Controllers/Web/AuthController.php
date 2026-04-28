<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('auth.login');
    }

    public function authorizes(Request $request)
    {
        $params = $request->all();

        return Users::authorizes($params, $request->method(), $request);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        
        return redirect('/login');
    }

}
