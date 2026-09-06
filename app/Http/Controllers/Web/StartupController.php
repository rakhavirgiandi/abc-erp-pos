<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class StartupController extends Controller
{
    public function index()
    {
        return view('startup');
    }
}