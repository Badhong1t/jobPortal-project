<?php

namespace App\Http\Controllers\Backend\web;

use App\Http\Controllers\Controller;
use App\Models\Company_Facilities;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CompanyFacilitiesController extends Controller
{

    function index(Request $request){

        if ($request->ajax()) {
            $data = Company_Facilities::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('description', function ($data) {

                    $description = Str::length($data->description) > 100 ? substr($data->description, 0,100). '....': $data->description;
                    $status = '<p>' . $description. '</p>';
                    return $status;

                })
                ->addColumn('action', function ($data) {

                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="'. route('backend.companyFacilities.edit', $data->id) .'" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-edit"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bxs-trash" ></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['description','action'])
                ->make(true);

            }

        return view("Backend.layouts.companyFacilities.index");

    }

    function create(){

        return view("Backend.layouts.companyFacilities.create");

    }

    function store(Request $request){

        // dd($request->all());
        $request->validate([

            'facility_title' => 'required|max:255',
            'facility_description'=> 'required',

        ]);

        // dd($request->all());
            $data = Company_Facilities::insert([

                'title' => $request->facility_title,
                'description'=> $request->facility_description,

            ]);


            if($data){

                return redirect()->route('backend.companyFacilities.index')->with('t-success','created successfully');

            }
            else{

                return redirect()->route('backend.companyFacilities.index')->with('t-error','creation failed');

            }

    }

    function edit($id){


        $data = Company_Facilities::find($id);

        return view('Backend.layouts.companyFacilities.edit', compact('data'));

    }

    function update(Request $request, $id){

            $request->validate([

                'facility_title' => 'required|max:255',
                'facility_description'=> 'required',

            ]);

            // dd($request->all());

            $data = Company_Facilities::find($id);

            $updated = $data->update([

                    'title' => $request->facility_title,
                    'description'=> $request->facility_description,

                ]);


            if($updated){

                return redirect()->route('backend.companyFacilities.index')->with('t-success','created successfully');

            }
            else{

                return redirect()->route('backend.companyFacilities.index')->with('t-error','creation failed');

            }

    }

    function destroy($id){

        $data = Company_Facilities::find($id);
        $data->delete();

        return response()->json([

            'success' => true,
            'message' => 'Compnay facilities deleted successfully',

        ]);

    }


}
