<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    //__category index method__//
    function category_index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return "<img width='70px' src='" . asset('storage/category/' .$data->image) . "' ></img>";
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
                                  <a href="' . route('category.edit', ['id'=>$data->id]) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-pencil"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bx-trash"></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['status','image','action'])
                ->make(true);
            }
        return view('backend.layouts.category.index');
    }

    //__category create page method__//
    function category_create(){
        return view('backend.layouts.category.create');
    }

    //__category store method__//
    function category_store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3048',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if($request->image) {
            $image = $request->image;
            $extension = $image->extension();
            $image_name = uniqid() . '.' . $extension;
            // create image manager with desired driver
            $manager = new ImageManager(new Driver());
            // read image from file system
            $image = $manager->read($image);
            $image->resize(150, 100);
            $image->save(public_path('storage/category/'.$image_name));

            $data=Category::insert([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $image_name,
                'created_at' => Carbon::now(),
            ]);
        }else{
            $data=Category::insert([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'created_at' => Carbon::now(),
            ]);
        }
        if ($data) {
            return redirect()->route('category.index')->with('t-success', 'Category created successfully.');
        } else {
            return redirect()->route('categorys.index')->with('t-error', 'Category failed created.');
        }
    }

    //__category edit method__//
    function category_edit($id){
        $category = Category::find($id);
        return view('backend.layouts.category.edit', compact('category'));
    }

    //__category delete method__//
    function category_delete($id){
         //image unlink // delete
        $category = Category::find($id);
        if($category->image != null){
            $delete_photo = public_path('storage/category/'.$category->image);
            unlink($delete_photo);
        }
        $category->delete();
        if (!$category) {
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

    //__category update method__//

    function category_update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3048',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = array();
        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);
        $data['updated_at'] = Carbon::now();
        $image=$request->file('image');
        if ($image) {
            //image delete
            unlink('storage/category/'.$request->old_image);
            //new image upload
            $image=$request->image;
            $extension = $image->extension();
            $image_name = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->resize(150, 100);
            $image->save(public_path('storage/category/'.$image_name));
            $data['image']=$image_name;
        }else{
            //old image upload
            $data['image'] = $request->old_image;
        }
        $update = Category::where('id', $id)->update($data);
        if ($update) {
            return redirect()->route('category.index')->with('t-success', 'Category updated successfully.');
        } else {
            return redirect()->route('category.index')->with('t-error', 'Category failed updated.');
        }
    }

    //__category status update method__//
    function category_status_update($id){
        $data = Category::where('id', $id)->first();
        if($data->status == 'active'){
            $data->status = 'inactive';
            $data->save();
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data' => $data,
            ]);
        }else{
            $data->status = 'active';
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data' => $data,
            ]);
        }
    }
}
