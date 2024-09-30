<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Country;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class JobPostController extends Controller
{
    //__Job Index method__//
    function job_index(Request $request)
    {
        if ($request->ajax()) {
            $data = Job::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return "<img width='70px' src='" . asset('storage/jobpost/' .$data->image) . "' ></img>";
                })
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch" style="margin-left:40px;">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status =="Active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('jobpost.edit', ['id'=>$data->id]) . '" type="button" class="btn btn-primary text-white" title="Edit">
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
        return view('backend.layouts.jobpost.index');
    }

    //__Job Create method__//
    function job_create()
    {
        $category = Category::where('status', 'active')->get();
        $country = Country::all();
        $company = Company::all();
        return view('backend.layouts.jobpost.create',compact('category','country','company'));
    }

    //__Job Store method__//
    function job_store(Request $request)
    {
        $request->validate([
            'job_name' => 'required',
            'job_details'=>'required',
            'short_description' => 'required|max:500',
            'category_id' => 'required',
            'country_id' => 'required',
            'company_id' => 'required',
            'address' => 'required',
            'end_date' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3072',
        ]);

        //image uploads
        $image = $request->image;
        $extension = $image->extension();
        $image_name = uniqid() . '.' . $extension;
        // create image manager with desired driver
        $manager = new ImageManager(new Driver());
        // read image from file system
        $image = $manager->read($image);
        $image->resize(600, 500);
        $image->save(public_path('storage/jobpost/'.$image_name));

        $store = Job::insert([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'country_id' => $request->country_id,
            'company_id' => $request->company_id,
            'job_name' => $request->job_name,
            'job_details' => $request->job_details,
            'short_description' => $request->short_description,
            'address' => $request->address,
            'end_date' => $request->end_date,
            'image' => $image_name,
            'created_at' => Carbon::now(),
            'salary' => $request->salary,
            'experience' => $request->experience,
        ]);
        if ($store) {
            return redirect()->route('jobpost.index')->with('t-success', 'Job Post successfully.');
        } else {
            return redirect()->route('jobpost.index')->with('t-error', 'Blog failed created.');
        }
    }
    //__jobpost edit method__//
    function job_edit($id){
        $data = Job::find($id);
        $category = Category::all();
        $country = Country::all();
        $company = Company::all();
        return view('backend.layouts.jobpost.edit',compact('data','category','country','company'));
    }
    //__jobpost update method__//
    function job_update(Request $request, $id){
        $request->validate([
            'job_name' => 'required',
            'job_details'=>'required',
            'short_description' => 'required|max:500',
            'category_id' => 'required',
            'country_id' => 'required',
            'company_id' => 'required',
            'address' => 'required',
            'end_date' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3072',
        ]);
        $data = array();
        $data['category_id'] = $request->category_id;
        $data['country_id'] = $request->country_id;
        $data['company_id'] = $request->company_id;
        $data['job_name'] = $request->job_name;
        $data['job_details'] = $request->job_details;
        $data['short_description'] = $request->short_description;
        $data['address'] = $request->address;
        $data['end_date'] = $request->end_date;
        $data['updated_at'] = Carbon::now();
        $data['salary'] = $request->salary;
        $data['experience'] = $request->experience;
        $image=$request->file('image');
        if ($image) {
            //image delete
            unlink('storage/jobpost/'.$request->old_image);
            //new image upload
            $image=$request->image;
            $extension = $image->extension();
            $image_name = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->resize(600, 500);
            $image->save(public_path('storage/jobpost/'.$image_name));
            $data['image']=$image_name;
        }else{
            //old image upload
            $data['image'] = $request->old_image;
        }
        $update = Job::where('id', $id)->update($data);
        if ($update) {
            return redirect()->route('jobpost.index')->with('t-success', 'Jobpost updated successfully.');
        } else {
            return redirect()->route('jobpost.index')->with('t-error', 'Jobpost failed updated.');
        }

    }
    //__jobpost delete method__//
    function job_delete($id){
        $data = Job::find($id);
        if($data->image != null){
            $delete_photo = public_path('storage/jobpost/'.$data->image);
            unlink($delete_photo);
        }
        $data->delete();
        if (!$data) {
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
    //__jobpost status update__//
    public function job_status_update($id)
    {
        $data = Job::where('id', $id)->first();
        if($data->status == 'Active') {
            // If the current status is active, change it to inactive
            $data->update(['status' => 'Inactive']);

            // Return JSON response indicating success with message and updated data
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data' => $data,
            ]);
        }else {
            // If the current status is inactive, change it to active
            $data->update(['status' => 'Active']);
            // Return JSON response indicating success with a message and updated data.
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data' => $data,
            ]);
        }
    }
}
