<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class DynamicpageController extends Controller
{
    //__page index method__//
    function pageindex(Request $request)
    {
        if ($request->ajax()) {
            $data = DynamicPage::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('page_title', function ($data) {
                    $page_title = $data->page_title;
                    $status = '<p>' . $page_title . ' </p>';
                    return $status;
                })
                ->addColumn('page_content', function ($data) {
                    $page_content = str::length($data->page_content) > 100 ? substr($data->page_content, 0, 100) . '...' : $data->page_content;
                    $status = '<p>' . $page_content . ' </p>';
                    return $status;
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
                                  <a href="'.route('dynamic_page.edit', ['id' => $data->id]).'" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bx bx-pencil"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bx bx-trash"></i>
                                </a>
                                </div>';
                })
                ->rawColumns(['page_title', 'page_content', 'status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.pages.index');
    }
    //__page create method__//
    function page_create()
    {
        return view('backend.layouts.pages.create');
    }

    //__page store method__//
    function page_store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'page_title' => 'required|string|max:100',
                'page_content' => 'required|string',
            ]);

            // If validation fails, redirect back with errors and input data

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $data = new DynamicPage();
            $data->page_title = $request->page_title;
            $data->page_slug = Str::slug($request->page_title);
            $data->page_content = $request->page_content;
            $data->save();

            return redirect()->route('dynamic_page.index')->with('t-success', 'Dynamic Page created successfully.');
        } catch (Exception $e) {
            return redirect()->route('dynamic_page.index')->with('t-success', 'Dynamic Page failed created.');
        }
    }
    //__page edit method__//
    function page_edit($id)
    {
        $data = DynamicPage::where('id', $id)->first();
        return view('backend.layouts.pages.edit', compact('data'));

    }
    //__page update method__//
    function page_update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'page_title' => 'required|string|max:100',
                'page_content' => 'required|string',
            ]);

            // If validation fails, redirect back with errors and input data

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $data = DynamicPage::findOrFail($id);
            $data->page_title = $request->page_title;
            $data->page_slug = Str::slug($request->page_title);
            $data->page_content = $request->page_content;
            $data->update();

            return redirect()->route('dynamic_page.index')->with('t-success', 'Dynamic Page Updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('dynamic_page.index')->with('t-success', 'Dynamic Page failed Updated.');
        }
    }
    //__page status Change method__//
    function status($id)
    {
        $data = DynamicPage::where('id', $id)->first();
        if ($data->status == 'active') {
            // If the current status is active, change it to inactive
            $data->status = 'inactive';
            $data->save();

            // Return JSON response indicating success with message and updated data
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data' => $data,
            ]);
        } else {
            // If the current status is inactive, change it to active
            $data->status = 'active';
            $data->save();

            // Return JSON response indicating success with a message and updated data.
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data' => $data,
            ]);
        }
    }

    //__page delete method__//
    function dynamicPageDelete($id){
        $page = DynamicPage::find($id);
        if(!$page){
            return response()->json(['success' => false, 'message' => 'Page not found.']);
        }else{
            $page->delete();
            return response()->json(['success' => true, 'message' => 'Page deleted successfully.']);
        }
    }
}
