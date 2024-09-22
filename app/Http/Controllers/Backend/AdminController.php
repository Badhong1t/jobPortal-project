<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Validation\Validator;

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

    function index(Request $request){

        return view('backend.partials.index');

    }

    function mailSettingUpdate(Request $request){

        $request->validate([

            'mail_mailer' => 'required|string',
            'mail_port' => 'required|string',
            'mail_from_address' => 'required|string',
            'mail_host' => 'required|string',
            'mail_password' => 'nullable|string',
            'mail_username' => 'nullable|string',
            'mail_encryption' => 'nullable|string',

        ]);

        try{

            $env_content = File::get(base_path('.env'));
            $line_break = "\n";
            $env_content = preg_replace([

                '/MAIL_MAILER=(.*)\s/',
                '/MAIL_PORT=(.*)\S/',
                '/MAIL_FROM_ADDRESS=(.*)\S/',
                '/MAIL_HOST=(.*)\s/',
                '/MAIL_PASSWORD=(.*)\s/',
                '/MAIL_USERNAME=(.*)\s/',
                '/MAIL_ENCRYPTION=(.*)\s/',

            ],[

                'MAIL_MAILER=' . $request->mail_mailer . $line_break,
                'MAIL_PORT=' . $request->mail_port . $line_break,
                'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $line_break,
                'MAIL_HOST=' . $request->mail_host . $line_break,
                'MAIL_PASSWORD=' . $request->mail_password . $line_break,
                'MAIL_USERNAME=' . $request->mail_username . $line_break,
                'MAIL_ENCRYPTION=' . $request->mail_encryption . $line_break,


            ],$env_content);

            if($env_content !== null){

                $env_content = File::put(base_path('.env'), $env_content);

        }

        return back()->with('t-success', 'updated successfully');


    }
    catch(Exception $e){

        return back()->with('t-error', 'failed to update');

    }

    return redirect()->back();

    }

}
