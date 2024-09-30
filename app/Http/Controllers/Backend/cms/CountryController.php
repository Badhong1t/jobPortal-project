<?php

namespace App\Http\Controllers\backend\cms;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{


    function index(Request $request){


        if ($request->ajax()) {
            $data = Country::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {

                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="'. route('backend.country.edit', $data->id) .'" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-edit"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bxs-trash" ></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("backend.layouts.countries.index");

    }

    function create(){

        return  view("backend.layouts.countries.create");

    }

    function store(Request $request){

        $request->validate([

            'country_name' => 'required|unique:countries|max:255',
            'country_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',

        ]);

        // dd($request->has('country_image'));

        if($request->hasFile('country_image')){

            $file = $request->file('country_image');
            $name = $file->getClientOriginalExtension();
            $filename = time().Str::random(10).'.'.$name;
            $filePath = $file->storeAs('uploads',$filename,'public');
            $path = Storage::url($filePath);
            $data = Country::create([

                'country_name' => $request->input('country_name'),
                'country_image' => $filename,

            ]);

            if($data){

                return redirect()->action([self::class, 'index'])->with('t-success', 'country created successfully');

            }

        }

            return  redirect()->action([self::class, 'index'])->with('t-error', 'country not created');



    }

    function edit($id){

        $data = Country::findOrFail($id);
        // dd($data);

        return view('backend.layouts.countries.edit', compact('data'));

    }

    function update(Request $request, $id){

        // dd($request->all());
        $request->validate([

            'country_name' =>  'required|max:255',
            'country_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',

        ]);

        $data = Country::findOrFail($id);

        if($request->hasFile('country_image')){

            $file = $request->file('country_image');
            $name = $file->getClientOriginalExtension();
            $filename = time().Str::random(10).'.'.$name;
            $filePath = $file->storeAs('uploads',$filename,'public');
            $path = Storage::url($filePath);


        }
        $updated = $data->update([

            'country_name' => $request->input('country_name'),
            'country_image' => $filename,

        ]);
        // dd($updated);

        if($updated){
            return redirect()->route('backend.country.index')->with('t-success', 'country updated successfully');


        }
        else{

            return redirect()->route('backend.country.index')->with('t-error', 'country updated failed');

        }

    }

    function destroy($id){

        $data = Country::findOrFail($id);

        $data->delete();
        return response()->json([

            'success' => true,
            'message' => 'country deleted successfully',

        ]);

    }

}
