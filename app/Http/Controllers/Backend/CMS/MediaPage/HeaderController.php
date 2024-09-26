<?php

namespace App\Http\Controllers\Backend\CMS\MediaPage;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class HeaderController extends Controller
{
    //__header index method__//

    public function header_index()
    {
        $data = DB::table('c_m_s')->get();
        return view('backend.layouts.header.index',compact('data'));
    }

    //__header update method__//
    public function header_update(Request $request)
    {
        $request->validate([
            'image_url' => 'nullable|image|mimes:jpeg,jpg,png,svg|max:3078',
        ]);
        $data = array();
        $data['title'] = $request->title;
        $image = $request->file('image_url');
        if ($image) {
            //image delete
            unlink('storage/header/'.$request->old_image_url);
            //new image upload
            $image = $request->image_url;
            $extension = $image->extension();
            $image_name = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->resize(800, 500);
            $image->save(public_path('storage/header/'.$image_name));
            $data['image_url'] = $image_name;

        }else{
            $data['image_url'] = $request->old_image_url;
        }
        $data['updated_at'] = Carbon::now();
        $update = DB::table('c_m_s')->where('id',$request->id)->update($data);
        if($update){
            return back()->with('t-success', 'Header updated successfully');
        }else{
            return back()->with('t-error', 'Something went wrong');
        }
    }
}
