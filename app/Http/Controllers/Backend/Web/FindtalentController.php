<?php

namespace App\Http\Controllers\Backend\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Exception;

class FindtalentController extends Controller
{
    //

    public function talentindex()
    {
        $findtalent = CMS::where("id",2)->first();
        return view('backend.layouts.find_talent.index', compact('findtalent'));
    }


    public function talentupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'sub_title' => 'required',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $talent = CMS::findOrFail(2);
            $talent->title = $request->title;
            $talent->description = $request->description;
            $talent->sub_title = $request->sub_title;

            if ($request->hasFile('image_url')) {
                if (File::exists(public_path('backend/uploads/') . $talent->image_url)) {
                    @unlink(public_path('backend/uploads/') . $talent->image_url);
                }


            $image = time() . '.' . $request->file('image_url')->getClientOriginalExtension();
            $request->file('image_url')->move(public_path('backend/uploads'), $image);

                // $logo = uploadImage($request->file('logo'), 'setting/logo');
                $talent->image_url = $image;
            }

            $talent->save();
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }
    }

}
