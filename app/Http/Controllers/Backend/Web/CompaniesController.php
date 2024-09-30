<?php

namespace App\Http\Controllers\Backend\Web;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FAQCompany;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompaniesController extends Controller
{
    //
    public function allcompanies(Request $request)
    {


        if ($request->ajax()) {
            $data = Company::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('company_log', function ($data) {
                    $url = url('backend/uploads/' . $data->company_log); // Use asset() for better handling
                    return '<img src="'.$url.'" alt="image" width="100" height="60" style="border-radius: 6px;">';
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
                                  <a href="' . route('company.edit',  $data->id) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bi bi-pencil"></i>Edit
                                  </a>
                                  <a href="' . route('company.delete',  $data->id) . '" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bi bi-trash"></i>Delete
                                </a>
                                </div>';
                })
                ->rawColumns(['action','company_log','status'])
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

      //__ status update method__//
      function companystatus($id)
      {
          $data = Company::where('id', $id)->first();
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

    //__companies FAQ index method__//

    public function companies_index(Request $request)
    {

        if ($request->ajax()) {
            $data = FAQCompany::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    $title = $data->title;
                    $status = '<p>' . $title . ' </p>';
                    return $status;
                })
                ->addColumn('description', function ($data) {
                    $description = str::length($data->description) > 200 ? substr($data->description, 0, 200) . '...' : $data->description;
                    $status = '<p>' . $description . ' </p>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('for_companies.edit', ['id'=>$data->id]) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-pencil"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bx-trash"></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['title','description','action'])
                ->make(true);
        }
        return view('backend.layouts.company.faq.index');
    }
    //__companies FAQ cretate method__//
    public function companies_create()
    {
        return view('backend.layouts.company.faq.create');
    }

    //__companies FAQ store method__//
    public function companies_store(Request $request)
    {
        $validator =  validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = new FAQCompany();
        $data->title = $request->title;
        $data->description = $request->description;
        $data->created_at = Carbon::now();
        $data->save();
        return redirect()->route('for_companies.index')->with('t-success', 'Created successfully!');
    }

    //__companies FAQ edit method__//
    public function companies_edit($id)
    {
        $data = FAQCompany::where('id', $id)->first();
        return view('backend.layouts.company.faq.edit', compact('data'));
    }

    //__companies FAQ update method__//
    public function companies_update(Request $request, $id)
    {
        $validator =  validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $update=FAQCompany::find($id)->update([
           'title' => $request->title,
           'description' => $request->description,
           'updated_at' => Carbon::now(),
        ]);
        if ($update) {
            return redirect()->route('for_companies.index')->with('t-success', 'Updated successfully.');
        } else {
            return redirect()->route('for_companies.index')->with('t-error', 'failed updated.');
        }
    }

    //__companies FAQ delete method__//
    public function companies_delete($id)
    {
        $data = FAQCompany::find($id);
        $data->delete();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }
        return response()->json([
            'success'=> true,
            'message' => 'FAQ deleted successfully',
        ]);
    }

}
