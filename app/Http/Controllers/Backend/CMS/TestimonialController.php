<?php

namespace App\Http\Controllers\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class TestimonialController extends Controller
{
    //__testimonial index page__//
    public function index()
    {
        $data = DB::table('c_m_s')->get();
        return view('backend.layouts.testimonial.index',compact('data'));
    }

    //__testimonial update method__//
    public function update(Request $request)
    {
        $request->validate([
            'image_url' => 'nullable|image|mimes:jpeg,jpg,png,svg|max:3078',
        ]);
        $data = array();
        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['button_text'] = $request->button_text;
        $image = $request->file('image_url');
        if ($image) {
            //image delete
            $image = CMS::find($request->id);
            if ($image->image_url != null) {
                $old_image = public_path('storage/testimonial/'.$image->image_url);
                unlink($old_image);
            }
            //new image upload
            $image = $request->image_url;
            $extension = $image->extension();
            $image_name = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->resize(600, 500);
            $image->save(public_path('storage/testimonial/'.$image_name));
            $data['image_url'] = $image_name;
        }else{
            $data['image_url'] = $request->old_image_url;
        }
        $data['updated_at'] = Carbon::now();
        $update = CMS::where('id',$request->id)->update($data);
        if($update){
            return back()->with('t-success', 'Testimonial updated successfully');
        }else{
            return back()->with('t-error', 'Something went wrong');
        }
    }
}

