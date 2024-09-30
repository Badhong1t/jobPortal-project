<?php

namespace App\Http\Controllers\Backend\Web;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Exception;

class SystemSettingController extends Controller
{
    //

    public function index()
    {
        $setting = SystemSetting::latest('id')->first();
        return view('backend.layouts.system_setting.index', compact('setting'));
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'system_name' => 'nullable',
            'description' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'mimes:jpeg,png,jpg,gif,svg,ico|max:2048',
            'copyright' => 'nullable',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $setting = SystemSetting::firstOrNew();
            $setting->system_name = $request->system_name;
            $setting->description = $request->description;
            $setting->copyright = $request->copyright;

            if ($request->hasFile('logo')) {
                if (File::exists(public_path('backend/uploads/') . $setting->logo)) {
                    @unlink(public_path('backend/uploads/') . $setting->logo);
                }


            $logo = time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('backend/uploads'), $logo);

                // $logo = uploadImage($request->file('logo'), 'setting/logo');
                $setting->logo = $logo;
            }

            if ($request->hasFile('favicon')) {
                if (File::exists(public_path('backend/uploads/') . $setting->favicon)) {
                    File::delete(public_path('backend/uploads/') . $setting->favicon);
                }
                $favicon = time() . '.' . $request->file('favicon')->getClientOriginalExtension();
                // $favicon = uploadImage($request->file('favicon'), 'setting/favicon');
                $setting->favicon = $favicon;
            }

            $setting->save();
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }
    }



    public function profileindex()
    {
        return view('backend.layouts.system_setting.profileindex');
    }

}
