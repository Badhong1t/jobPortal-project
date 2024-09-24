<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    //__admin profile edit method__//
    public function profile_edit()
    {
        return view('backend.auth.profile_edit');
    }

    //__admin profile update method__//
    public function profile_update(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'email' => 'required|email',
            ]);
            // If validation fails, redirect back with errors and input data
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            User::find(Auth::id())->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            return redirect()->back()->with('t-success', 'Admin Profile Updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('t-success', 'Admin Profile Updated failed.');
        }
    }
}
