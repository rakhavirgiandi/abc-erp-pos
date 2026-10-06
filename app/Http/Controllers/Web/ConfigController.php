<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index(Request $request)
    {   
        return view('config.index');
    }

    public function database(Request $request)
    {   
        return view('config.database');
    }

    public function activityLog (Request $request)
    {
        return view('config.activity_log');
    }
}