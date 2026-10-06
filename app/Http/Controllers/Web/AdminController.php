<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {   
        // dd(config('database.connections.pgsql_companies'));

        return view('companies.v1.admin.dashboard', ['title' => 'Dashboard']);
    }
}