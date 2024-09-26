<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Exception;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use App\Models\Social_Media_Fields;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    //Mail view method

    function mailView(Request $request){

        return view('backend.partials.mailSystem');

    }

    //mail update settings method

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

    // return redirect()->back();

    }

    //social media view method

    function socialMediaView(){

        $social_link = Social_Media_Fields::latest('id')->get();
        return view('backend.partials.socialMedia', compact('social_link'));

    }

    //Social media update settings method

    function socialMediaUpdate(Request $request){

        // Validate the request
        $validator = Validator::make($request->all(), [
            'social_media.*'    => 'required|string',
            'profile_link.*'    => 'required|url',
            'social_media_id.*' => 'sometimes|nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $idsToUpdate = collect($request->social_media_id)->filter()->all();

            // Update existing entries and collect their IDs
            foreach ($request->social_media as $index => $media) {
                $profileLink   = $request->profile_link[$index] ?? null;
                $socialMediaId = $request->social_media_id[$index] ?? null;

                if ($media && $profileLink) {
                    $socialMedia = $socialMediaId ? Social_Media_Fields::find($socialMediaId) : new Social_Media_Fields();
                    $socialMedia->social_media = $media;
                    $socialMedia->profile_link = $profileLink;
                    $socialMedia->save();

                    // If updating, remove this ID from the $idsToUpdate array
                    if (($key = array_search($socialMediaId, $idsToUpdate)) !== false) {
                        unset($idsToUpdate[$key]);
                    }
                }
            }

            Social_Media_Fields::whereIn('id', $idsToUpdate)->delete();

            return back()->with('t-success', 'Social media links updated successfully.');
        } catch (Exception) {
            return back()->with('t-error', 'Social media links failed update.');
        }
    }

    public function destroy( $id) {
        try {
            // Correctly delete the SocialMedia record by ID
            Social_Media_Fields::destroy($id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Social media link deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete social media link.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
