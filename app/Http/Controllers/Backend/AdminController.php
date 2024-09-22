<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //__admin login method__//
    function admin_login()
    {
        return view('backend.auth.login');
    }
    //__admin dashoard method__//
    public function dashboard()
    {
        return view('backend.layouts.dashboard');
    }
}
