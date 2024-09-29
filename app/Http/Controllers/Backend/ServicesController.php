<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ServicesController extends Controller
{
    //__services index page__//
    public function services_index(Request $request)
    {

        if ($request->ajax()) {
            $data = Services::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('services_name', function ($data) {
                    $service_name = $data->service_name;
                    $status = '<p>' . $service_name . ' </p>';
                    return $status;
                })
                ->addColumn('description', function ($data) {
                    $description = str::length($data->description) > 100 ? substr($data->description, 0, 100) . '...' : $data->description;
                    $status = '<p>' . $description . ' </p>';
                    return $status;
                })
                ->addColumn('icon_path', function ($data) {
                    return "<img width='50px' src='" . asset('storage/services_icons/' .$data->icon_path) . "' ></img>";
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
                                  <a href="'.route('services.edit', ['id' => $data->id]).'" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-pencil"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bx-trash"></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['service_name', 'description', 'status', 'icon_path', 'action'])
                ->make(true);
        }
        // Display the services index page with all services data
        return view('backend.layouts.services.index');
    }


    //__services create page__//
    public function services_create()
    {
        return view('backend.layouts.services.create');
    }

    //__services store page__//
    public function services_store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'required|max:3000',
            'icon_path' => 'nullable|mimes:png,svg|max:2048',
        ]);

        $icon_path = $request->icon_path;
        $extension = $icon_path->extension();
        $icon_path_name = time() . '.' . $extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($icon_path);
        $image->resize(50, 50);
        $image->save(public_path('storage/services_icons/'.$icon_path_name));

        $store = Services::insert([
            'service_name' => $request->service_name,
            'description' => $request->description,
            'icon_path' => $icon_path_name,
            'created_at' => Carbon::now(),
        ]);
        if ($store) {
            return redirect()->route('services.index')->with('t-success', 'Service Created successfully!');
        }else{
            return redirect()->route('services.index')->with('t-error', 'Created failed!');
        }

    }

    //__services edit page__//
    public function services_edit($id)
    {
        $data = Services::find($id);
        return view('backend.layouts.services.edit', compact('data'));
    }
    //__services update method__//
    public function services_update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'required|max:3000',
            'icon_path' => 'nullable|mimes:png,svg|max:2048',
        ]);
        $icon_path = $request->icon_path;
        if (!$icon_path) {
            $icon_path = $request->old_icon_path;
        }
        $extension = $icon_path->extension();
        $icon_path_name = time() . '.' . $extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($icon_path);
        $image->resize(50, 50);
        $image->save(public_path('storage/services_icons/'.$icon_path_name));
        $data = Services::find($id);
        $data->service_name = $request->service_name;
        $data->description = $request->description;
        $data->icon_path = $icon_path_name;
        $data->updated_at = Carbon::now();
        $data->save();
        if (!$data) {
            return redirect()->route('services.index')->with('t-error', 'Updated failed!');
        }
        return redirect()->route('services.index')->with('t-success', 'Services Updated successfully!');

    }

    //__services_delete method__//
    function services_delete($id){
        $data = Services::find($id);
        if($data->image != null){
            $delete_photo = public_path('storage/blog/'.$data->image);
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

    //__services_status_update method__//
    public function services_status_update($id)
    {
        $data = Services::find($id);
        if ($data->status == 'active') {
            // If the current status is active, change it to inactive
            $data->status = 'inactive';
            $data->save();
            // Return JSON response indicating success with message and updated data
            return response()->json([
                'success' => false,
                'message' => 'Services Unpublished Successfully.',
                'data' => $data,
            ]);
        } else {
            // If the current status is inactive, change it to active
            $data->status = 'active';
            $data->save();
            // Return JSON response indicating success with a message and updated data.
            return response()->json([
                'success' => true,
                'message' => 'Services Published Successfully.',
                'data' => $data,
            ]);
        }

    }

}
