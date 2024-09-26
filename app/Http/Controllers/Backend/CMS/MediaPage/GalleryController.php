<?php

namespace App\Http\Controllers\Backend\CMS\MediaPage;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GalleryController extends Controller
{
    //__gallery page meyhod__//

    public function gallery_index(Request $request)
    {
        if ($request->ajax()) {
            $data = Gallery::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return "<img width='70px' src='" . asset('storage/gallery/' .$data->image) . "' ></img>";
                })
                ->addColumn('title', function ($data) {
                    $title = $data->title;
                    $status = '<p>' . $title . ' </p>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('gallery_image.edit', ['id'=>$data->id]) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-pencil"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bx-trash"></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['title','image','action'])
                ->make(true);
            }
        return view('backend.layouts.gallery.index');
    }

    //__gallery create page meyhod__//
    function gallery_create()
    {
        return view('backend.layouts.gallery.create');
    }

    //__gallery store method__//
    function gallery_store(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3078',
        ]);
        if($request->image) {
            $image = $request->image;
            $extension = $image->extension();
            $image_name = uniqid() . '.' . $extension;
            // create image manager with desired driver
            $manager = new ImageManager(new Driver());
            // read image from file system
            $image = $manager->read($image);
            $image->resize(400, 200);
            $image->save(public_path('storage/gallery/'.$image_name));

            $data=Gallery::insert([
                'title' => $request->title,
                'image' => $image_name,
                'created_at' => Carbon::now(),
            ]);
        }else{
            $data=Gallery::insert([
                'title' => $request->title,
                'created_at' => Carbon::now(),
            ]);
        }
        if ($data) {
            return redirect()->route('gallery_image.index')->with('t-success', 'Gallery Image created successfully.');
        } else {
            return redirect()->route('gallery_image.index')->with('t-error', 'Gallery Image failed created.');
        }
    }

    //__gallery edit page meyhod__//
    function gallery_edit($id)
    {
        $gallery_image = Gallery::find($id);
        return view('backend.layouts.gallery.edit', compact('gallery_image'));
    }

    //__gallery image update method__//
    function gallery_update(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3078',
        ]);
        $data = array();
        $data['title'] = $request->title;
        $data['updated_at'] = Carbon::now();
        $image=$request->file('image');
        if ($image) {
            //image delete
            unlink('storage/gallery/'.$request->old_image);
            //new image upload
            $image=$request->image;
            $extension = $image->extension();
            $image_name = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->resize(150, 100);
            $image->save(public_path('storage/gallery/'.$image_name));
            $data['image']=$image_name;
        }else{
            //old image upload
            $data['image'] = $request->old_image;
        }
        $update = Gallery::where('id', $request->id)->update($data);
        if ($update) {
            return redirect()->route('gallery_images.index')->with('t-success', 'Gallery Image updated successfully.');
        } else {
            return redirect()->route('gallery_images.index')->with('t-error', 'Gallery failed updated.');
        }
    }

    //__gallery image delete method__//
    function gallery_delete($id)
    {
        $gallery_image = Gallery::find($id);
        //image unlink // delete
        if($gallery_image->image != null){
            $delete_photo = public_path('storage/gallery/'.$gallery_image->image);
            unlink($delete_photo);
        }
        $gallery_image->delete();
        if (!$gallery_image) {
             return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Category Deleted successfully.'
        ]);
    }
}
