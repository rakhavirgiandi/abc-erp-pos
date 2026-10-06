<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class SetupController extends Controller
{
    public function adminPassword(Request $request)
    {
        return view('setup.admin_password');
    }
}