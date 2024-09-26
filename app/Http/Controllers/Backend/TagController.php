<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    public function tag_index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tag::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    $name = $data->name;
                    $status = '<p>' . $name . ' </p>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('tag.edit', ['id'=>$data->id]) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-pencil"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bx-trash"></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['name','action'])
                ->make(true);
        }
        return view('backend.layouts.tag.index');
    }
    //__tag create method__//
    public function tag_create(Request $request)
    {
        return view('backend.layouts.tag.create');
    }

    //__tag store method__//
    public function tag_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:tags',
        ]);
        // If validation fails, redirect back with errors and input data
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $data = new Tag();
            $data->name = $request->name;
            $data->created_at = Carbon::now();
            $data->save();
            return redirect()->route('tag.index')->with('t-success', 'Tag created successfully.');
        } catch (Exception $e) {
            return redirect()->route('tag.index')->with('t-error', 'Tag created failed.');
        }
    }

    //__tag edit method__//
    public function tag_edit($id)
    {
        $tag = Tag::find($id);
        return view('backend.layouts.tag.edit', compact('tag'));
    }

    //__tag update method__//
    public function tag_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        // If validation fails, redirect back with errors and input data
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $data = Tag::find($id);
            $data->name = $request->name;
            $data->updated_at = Carbon::now();
            $data->update();
            return redirect()->route('tag.index')->with('t-success', 'Tag updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('tag.index')->with('t-error', 'Tag updated failed.');
        }
    }

    //__teg delete method__//
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if(!$tag){
            return response()->json(['success' => false, 'message' => 'Tag not found.']);
        }else{
            $tag->delete();
            return response()->json(['success' => true, 'message' => 'Tag deleted successfully.']);
        }
    }
}
