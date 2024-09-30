<?php

namespace App\Http\Controllers\Backend\cms\findWork;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FindWorkController extends Controller
{

    function index(Request $request){

        $data = CMS::all();

        return view('backend.layouts.findWork.index', compact('data'));

    }

    function update(Request $request){

        $validated = $request->validate([

            'image_url' => 'images|mimes:jpg,jpeg,png,svg|min:2048',

        ]);

        if($request->hasFile('image')){

            $file = $request->file('image');
            // dd($file);
            $name = $file->getClientOriginalExtension();
            $filename = time().Str::random(10).'.'.$name;
            $filePath = $file->storeAs('uploads',$filename,'public');
            $path = Storage::url($filePath);


        }


        $data = CMS::findOrFail(1);

            $updated = $data->update([

                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'image_url' => $filename,
                'sub_description' => $request->short_description,
                'description' => $request->full_description,

            ]);

            if($updated){

                return back()->with('t-success','Content updated successfully');

            }

        return back()->with('t-error','Content failed to update');

    }

}
