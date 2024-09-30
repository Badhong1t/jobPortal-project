<?php

namespace App\Http\Controllers\Backend\Web;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    //
    public function allcontacts(Request $request)
    {
        // $data = CompanyBranch::all();
        // dd($data->toArray());

        if ($request->ajax()) {
            $data = Contact::with('company')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("company", function ($data) {
                    return $data->company->company_name;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('contacts.delete',  $data->id) . '"  onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bi bi-trash"></i>Delete
                                </a>
                                </div>';
                })

                ->rawColumns(['company','action'])
                ->make(true);
        }
        return view('backend.layouts.contacts.index');
    }

    // Show the form for creating a new award
    public function contactcreate()
    {
        $companys = Company::all();
        return view('backend.layouts.contacts.create', compact('companys'));
    }

    // Store a newly created award in storage
    public function contactstore(Request $request)
    {
       $validator = Validator::make( $request->all(),[
            'name' => 'required|string',
            'message' => 'required|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|string|max:255',
            'company' => 'exists:companies,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $contact = new Contact();
            $contact->name = $request->name;
            $contact->message = $request->message;
            $contact->phone = $request->phone;
            $contact->email = $request->email;
            $contact->company_id = $request->company;
            $contact->save();
        return back()->with('t-success', 'store successfully');

        } catch (Exception $e) {
        return back()->with('t-error', 'Failed to store');
        }
    }


    // Remove the specified award from storage
    public function contactdelete($id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();
        return  response()->json([
            'success'=> true,
        ]);
    }
}
