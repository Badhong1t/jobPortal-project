<?php

namespace App\Http\Controllers\Backend\Web;

use Exception;
use App\Models\Featuredjob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
// use Symfony\Component\HttpFoundation\File\File;

class FeaturejobController extends Controller
{
    //
    public function allfeature(Request $request)
    {


        if ($request->ajax()) {
            $data = Featuredjob::all();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('feature', function ($data) {
                    return $data->feature; // Adjust according to your model
                })
                ->addColumn('price', function ($data) {
                    return $data->price; // Adjust according to your model
                })
                ->addColumn('details', function ($data) {
                    return $data->details; // Adjust according to your model
                })
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch" style="margin-left:40px;">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == 1) {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('featurejob.edit',  $data->id) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bi bi-pencil"></i>Edit
                                  </a>
                                  <a href="' . route('featurejob.delete',  $data->id) . '" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bi bi-trash"></i>Delete
                                </a>
                                </div>';
                })

                ->rawColumns(['feature','price','details','action','status'])
                ->make(true);
        }
        return view('backend.layouts.feature_job.index');
    }

    // Show the form for creating a new company
    public function featurejobcreate()
    {
        return view('backend.layouts.feature_job.create');
    }

      // Store a newly created feature in storage
    public function featurejobstore(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'feature' => 'required|string|max:255',
            'price' => 'required',
            'status' => 'required',
            'details' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $feature = new Featuredjob();
            $feature->feature = $request->feature;
            $feature->price = $request->price;
            $feature->status = $request->status;
            $feature->details = $request->details;

            $feature->save();
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }

    }


    public function featurejobedit( $id)

    {
        $job = Featuredjob::findOrFail($id);
        return view('backend.layouts.feature_job.edit', compact('job'));
    }

    // Update the specified company in storage
    public function featurejobupdate(Request $request,  $id)
    {
        // $job = Featuredjob::findOrFail($id);
        $validator =  validator::make($request->all(),[
            'feature' => 'required',
            'price' => 'required',
            'details' => 'required',
        ]);



        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $job = Featuredjob::findOrFail($id);
            $job->feature = $request->feature;
            $job->price = $request->price;
            $job->details = $request->details;
            $job->status = $request->status;


            $job->save();

            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }
    }

      //__ status update method__//
      function featurejobstatus($id)
      {
          $data = Featuredjob::where('id', $id)->first();
          // dd($data);
          if ($data->status == 1) {
              // If the current status is active, change it to inactive
              $data->status = 0;
              $data->save();

              // Return JSON response indicating success with message and updated data
              return response()->json([
                  'success' => false,
                  'message' => 'Successfully.',
                  'data' => $data,
              ]);
          } else {
              // If the current status is inactive, change it to active
              $data->status = 1;
              $data->save();

              // Return JSON response indicating success with a message and updated data.
              return response()->json([
                  'success' => true,
                  'message' => 'Error.',
                  'data' => $data,
              ]);
          }
      }



    // Remove the specified company from storage
    public function featurejobdelete($id)
    {
        $job = Featuredjob::findOrFail($id);
        $job->delete();

        return  response()->json([
            'success'=> true,
        ]);
    }
}
