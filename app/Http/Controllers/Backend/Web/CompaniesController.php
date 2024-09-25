<?php

namespace App\Http\Controllers\Backend\Web;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
// use Symfony\Component\HttpFoundation\File\File;

class CompaniesController extends Controller
{
    //
    public function allcompanies(Request $request)
    {


        if ($request->ajax()) {
            $data = Company::latest();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('company_name', function ($data) {
                    return $data->company_name; // Adjust according to your model
                })
                ->addColumn('phone', function ($data) {
                    return $data->phone; // Adjust according to your model
                })
                ->addColumn('email', function ($data) {
                    return $data->email; // Adjust according to your model
                })
                ->addColumn('country_name', function ($data) {
                    return $data->country_name; // Adjust according to your model
                })
                ->addColumn('company_log', function ($data) {
                    $url = url('backend/uploads/' . $data->company_log); // Use asset() for better handling
                    return '<img src="'.$url.'" alt="image" width="100" height="60" style="border-radius: 6px;">';
                })



                ->addColumn('address', function ($data) {
                    return $data->address; // Adjust according to your model
                })

                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch" style="margin-left:40px;">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "1") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })


                // ->addColumn('status', function ($data) {
                //     return $data->status;
                // })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('company.edit',  $data->id) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bi bi-pencil"></i>Edit
                                  </a>
                                  <a href="' . route('company.delete',  $data->id) . '" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bi bi-trash"></i>Delete
                                </a>
                                </div>';
                })

                ->rawColumns(['company_name','phone','email','action','country_name','company_log','address','status'])
                ->make(true);
        }
        return view('backend.layouts.company.index');
    }

    // Show the form for creating a new company
    public function companycreate()
    {
        return view('backend.layouts.company.create');
    }

      // Store a newly created company in storage
    public function companystore(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'company_name' => 'required|string|max:255',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:companies',
            'status' => 'required',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $company = new Company();
            $company->company_name = $request->company_name;
            // $company->company_log = $request->logo;
            $company->country_name = $request->country_name;
            $company->phone = $request->phone;
            $company->email = $request->email;
            $company->status = $request->status;
            $company->address = $request->description;
            // $company->copyright = $request->copyright;

            if ($request->hasFile('logo')) {

            $logo = time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('backend/uploads'), $logo);

                $company->company_log = $logo;
            }

            $company->save();
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }

    }

      // Display the specified company
    // public function show(Company $company)
    // {
    //     return view('companies.show', compact('company'));
    // }

      // Show the form for editing the specified company
    public function companyedit(Company $companys, $id)

    {
        $company = Company::findOrFail($id);
        return view('backend.layouts.company.edit', compact('company'));
    }

    // Update the specified company in storage
    public function companyupdate(Request $request,  $id)
    {
        $company = Company::findOrFail($id);
        $validator =  validator::make($request->all(),[
            'company_name' => 'required',
            'company_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country_name' => 'required',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:companies,email,' . $company->id,

        ]);



        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $company = Company::findOrFail($id);
            $company->company_name = $request->company_name;
            $company->country_name = $request->country_name;
            $company->phone = $request->phone;
            $company->email = $request->email;
            $company->status = $request->status;
            $company->address = $request->description;

            if ($request->hasFile('logo')) {
                if (File::exists(public_path('backend/uploads').$company->logo)) {
                    @unlink(public_path('backend/uploads').$company->logo);
                }


            $logo = time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('backend/uploads'), $logo);

                $company->company_log = $logo;
            }
            $company->save();

            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }


    }

    // Remove the specified company from storage
    public function companydelete($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        if(File::exists(public_path('backend/uploads/'. $company->company_log))){
            @unlink(public_path('backend/uploads/'. $company->company_log));
        }
        return  response()->json([
            'success'=> true,
        ]);
    }
}
