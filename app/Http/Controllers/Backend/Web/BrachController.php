<?php

namespace App\Http\Controllers\Backend\Web;

use Exception;
use App\Models\Company;
use App\Models\Companyaward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyBranch;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
// use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\File;

class BrachController extends Controller
{
    //
    public function allbranch(Request $request)
    {
        // $data = CompanyBranch::all();
        // dd($data->toArray());

        if ($request->ajax()) {
            $data = CompanyBranch::with('company')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("company", function ($data) {
                    return $data->company->company_name;
                })
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch" style="margin-left:40px;">';
                    $status .= ' <input for="customSwitch' . $data->id . '" onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == 1) {
                        $status .= "checked";
                    }
                    $status .= '><label  class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('companybranch.edit',  $data->id) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bi bi-pencil"></i>Edit
                                  </a>
                                  <a href="' . route('company.delete',  $data->id) . '"  onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bi bi-trash"></i>Delete
                                </a>
                                </div>';
                })

                ->rawColumns(['company','status','action'])
                ->make(true);
        }
        return view('backend.layouts.companybranch.index');
    }

    // Show the form for creating a new award
    public function companybranchcreate()
    {
        $companys = Company::all();
        return view('backend.layouts.companybranch.create', compact('companys'));
    }

    // Store a newly created award in storage
    public function companybranchstore(Request $request)
    {
       $validator = Validator::make( $request->all(),[
            'branch_name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'status' => 'required',
            'company_id' => 'exists:companies,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $branch = new CompanyBranch();
            $branch->branch_name = $request->branch_name;
            $branch->address = $request->address;
            $branch->phone = $request->phone;
            $branch->email = $request->email;
            $branch->company_id = $request->company;
            $branch->status = $request->status;
            $branch->save();
            return back()->with('t-success', 'store successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to store');
        }
    }

    // Display the specified award
    // public function show(CompanyAward $companyAward)
    // {
    //     return view('companyawards.show', compact('companyAward'));
    // }

    // Show the form for editing the specified award
    public function companybranchedit($id)
    {
        $companybranch = CompanyBranch::find($id);
        $companys = Company::all();
        return view('backend.layouts.companybranch.edit', compact('companybranch','companys'));
    }

    // Update the specified award in storage
    public function companybranchupdate(Request $request,  $id)
    {
        $validator = Validator::make($request->all(),[
            'branch_name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'status' => 'required',
            'company_id' => 'exists:companies,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $branch = CompanyBranch::findOrFail($id);
            $branch->branch_name = $request->branch_name;
            $branch->address = $request->address;
            $branch->phone = $request->phone;
            $branch->email = $request->email;
            $branch->company_id = $request->company;
            $branch->status = $request->status;
            $branch->save();
            return back()->with('t-success', 'store successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to store');
        }
    }

        //__ status update method__//
        function companybranchstatus($id)
        {
            $data = CompanyBranch::where('id', $id)->first();
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
                    'message' => 'error.',
                    'data' => $data,
                ]);
            }
        }

    // Remove the specified award from storage
    public function companybranchdelete($id)
    {
        $companybranch = CompanyBranch::findOrFail($id);

        $companybranch->delete();
        return  response()->json([
            'success'=> true,
        ]);
    }
}
