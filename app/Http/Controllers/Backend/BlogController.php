<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogController extends Controller
{
    //__blog index method__//
    public function blog_index(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return "<img width='70px' src='" . asset('storage/blog/' .$data->image) . "' ></img>";
                })
                ->addColumn('title', function ($data) {
                    $title = $data->title;
                    $status = '<p>' . $title . ' </p>';
                    return $status;
                })
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch" style="margin-left:40px;">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('blog.post.edit', ['id'=>$data->id]) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-pencil"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bx-trash"></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['title','status','image','action'])
                ->make(true);
            }
        return view('backend.layouts.blog.index');
    }

    //__blog create method__//
    public function blog_create()
    {
        $tags = Tag::all();
        return view('backend.layouts.blog.create', compact('tags'));
    }

    //__blog store method__//
    public function blog_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png,svg|max:30720',
            'description' => 'required',
            'youtube_link' => 'nullable|url',
            'status' => 'required|in:Active,Inactive',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);
        // If validation fails, redirect back with errors and input data
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        //image uploads
        $image = $request->image;
        $extension = $image->extension();
        $image_name = uniqid() . '.' . $extension;
        // create image manager with desired driver
        $manager = new ImageManager(new Driver());
        // read image from file system
        $image = $manager->read($image);
        $image->resize(300, 300);
        $image->save(public_path('storage/blog/'.$image_name));

       $data = Blog::insert([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'image' => $image_name,
            'description' => $request->description,
            'youtube_link' => $request->youtube_link,
            'status' => $request->status,
            'created_at' => Carbon::now(),
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'tags' => implode(',', $request->tags),
        ]);
        if ($data) {
            return redirect()->route('blog.index')->with('t-success', 'Blog created successfully.');
        } else {
            return redirect()->route('blog.index')->with('t-error', 'Blog failed created.');
        }
    }

    //__blog post edit method__//
    public function blog_post_edit($id)
    {
        $data = Blog::find($id)->first();
        $tags =  Tag::select('id','name')->get();
        return view('backend.layouts.blog.edit', compact('data', 'tags'));
    }

    //__blog update method__//
    public function blog_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,svg|max:3078',
            'description' => 'required',
            'youtube_link' => 'nullable|url',
        ]);
        // If validation fails, redirect back with errors and input data
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = array();
        $data['title'] = $request->title;
        $data['slug'] = Str::slug($request->title);
        $data['description'] = $request->description;
        $data['youtube_link'] = $request->youtube_link;
        $data['updated_at'] = Carbon::now();
        $data['meta_title'] = $request->meta_title;
        $data['meta_keywords'] = $request->meta_keywords;
        $data['meta_description'] = $request->meta_description;
        $data['tags'] = implode(',', $request->tags);
        $image=$request->file('image');
        if ($image) {
            //image delete
            unlink('storage/blog/'.$request->old_image);
            //new image upload
            $image=$request->image;
            $extension = $image->extension();
            $file_name = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->resize(300, 300);
            $image->save(public_path('storage/blog/'.$file_name));
            $data['image']=$file_name;
        }else{
            //old image upload
            $data['image'] = $request->old_image;
        }
        DB::table('blogs')->where('id', $id)->update($data);
        return redirect()->route('blog.index')->with('t-success', 'Blog Updated Successfully.');

    }

    //__blog status update method__//
    function blog_status_update($id)
    {
        $data = Blog::where('id', $id)->first();
        if ($data->status == 'active') {
            // If the current status is active, change it to inactive
            $data->status = 'inactive';
            $data->save();

            // Return JSON response indicating success with message and updated data
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data' => $data,
            ]);
        } else {
            // If the current status is inactive, change it to active
            $data->status = 'active';
            $data->save();

            // Return JSON response indicating success with a message and updated data.
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data' => $data,
            ]);
        }
    }

    //__blog delete method__//
    public function blog_delete($id)
    {
        //image unlink // delete
        $blog = Blog::find($id);
        if($blog->image != null){
            $delete_photo = public_path('storage/blog/'.$blog->image);
            unlink($delete_photo);
        }
        $blog->delete();
        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully.'
        ]);
    }
}
