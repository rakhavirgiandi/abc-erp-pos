<?php

namespace App\Http\Controllers\Web;

use Auth;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        if (Session::get('_login')) {
            return redirect('/');
        }
    }

    public function login()
    {
        $title = 'Login';

        return view('auth.'.__FUNCTION__, [
            'title' => $title
        ]);
    }

    public function register()
    {
        $title = 'Register';

        return view('auth.'.__FUNCTION__, [
            'title' => $title
        ]);
    }

    public function session(Request $request)
    {
        $params = $request->all();

        $request->session()->flush();
        $request->session()->put('_login', true);
        $request->session()->put('_id', $params['id']);
        $request->session()->put('_access_token', $params['access_token']);
        $request->session()->put('_name', $params['name']);
        $request->session()->put('_email', $params['email']);
        $request->session()->put('_phone', $params['phone']);
        $request->session()->put('_is_access_to_pos', true);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function forgotPassword()
    {
        $title = 'Lupa Password';

        return view('auth.forgot-password', [
            'title' => $title
        ]);
    }

    public function newPassword($code)
    {
        $title = 'Set Password';

        return view('auth.new-password', [
            'title' => $title,
            'code' => $code
        ]);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        
        return redirect('/');
    }

    public function deleteRequest()
    {
        return view('auth.delete-request');
    }
}
