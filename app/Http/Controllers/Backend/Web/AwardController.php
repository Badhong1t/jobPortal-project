<?php

namespace App\Http\Controllers\Backend\Web;

use Exception;
use App\Models\Company;
use App\Models\Companyaward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
// use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\File;

class AwardController extends Controller
{
    //
    public function allaward(Request $request)
    {
        $data = Companyaward::all();
        // dd($data->toArray());

        if ($request->ajax()) {
            $data = Companyaward::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("company", function ($data) {
                    return $data->company->company_name;
                })

                ->addColumn('award_image', function ($data) {
                    $url = url('backend/uploads/' . $data->award_image); // Use asset() for better handling
                    return '<img src="'.$url.'" alt="image" width="100" height="60" style="border-radius: 6px;">';
                })

                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('companyaward.edit',  $data->id) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bi bi-pencil"></i>Edit
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bi bi-trash"></i>Delete
                                </a>
                                </div>';
                })

                ->rawColumns(['award_image','company','action'])
                ->make(true);
        }
        return view('backend.layouts.companyaward.index');
    }

    // Show the form for creating a new award
    public function companyawardcreate()
    {
        $companys = Company::all();
        return view('backend.layouts.companyaward.create', compact('companys'));
    }

    // Store a newly created award in storage
    public function companyawardstore(Request $request)
    {
       $validator = Validator::make( $request->all(),[
            'award_name' => 'required|string|max:255',
            'award_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'company_id' => 'exists:companies,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $award = new Companyaward();
            $award->award_name = $request->award_name;
            $award->date = $request->date;
            $award->month = $request->month;
            $award->year = $request->year;
            $award->company_id = $request->company;
            if ($request->hasFile('award_image')) {
            $image = time() . '.' . $request->file('award_image')->getClientOriginalExtension();
            $request->file('award_image')->move(public_path('backend/uploads'), $image);

            $award->award_image = $image;
            }
            $award->save();
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
    public function companyawardedit(CompanyAward $companyAward,$id)
    {
        $companyaward = CompanyAward::find($id);
        $companys = Company::all();
        return view('backend.layouts.companyaward.edit', compact('companyaward','companys'));
    }

    // Update the specified award in storage
    public function companyawardupdate(Request $request, CompanyAward $companyAward , $id)
    {
        $validator = Validator::make($request->all(),[
            'award_name' => 'required|string|max:255',
            'award_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'company_id' => 'exists:companies,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $award = CompanyAward::find($id);
            $award->award_name = $request->award_name;
            $award->date = $request->date;
            $award->month = $request->month;
            $award->year = $request->year;
            $award->company_id = $request->company;
            if ($request->hasFile('award_image')) {
                if(File::exists(public_path('backend/uploads/'. $award->award_image))){
                    @unlink(public_path('backend/uploads/'. $award->award_image));
                }
            $image = time() . '.' . $request->file('award_image')->getClientOriginalExtension();
            $request->file('award_image')->move(public_path('backend/uploads'), $image);

            $award->award_image = $image;
            }
            $award->save();
            return back()->with('t-success', 'Award updated successfully.');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to store');
        }
    }

    // Remove the specified award from storage
    public function companyawarddelete($id)
    {
        $companyAward = CompanyAward::findOrFail($id);

        $companyAward->delete();
        if(File::exists(public_path('backend/uploads/'. $companyAward->award_image))){
            @unlink(public_path('backend/uploads/'. $companyAward->award_image));
        }
        return  response()->json([
            'success'=> true,
        ]);
    }
}
