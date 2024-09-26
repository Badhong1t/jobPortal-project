<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Socialmedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class SocialmediaController extends Controller
{
    //__socialMedia index method__//
    function Socialmedia()
    {
        $social_link = Socialmedia::latest('id')->get();
        return view('backend.layouts.social_media.index', compact('social_link'));
    }

    //__socialMedia update method__//
    function update(Request $request){
        $validator = validator::make($request->all(), [
            'social_media.*'    => 'required|string',
            'profile_link.*'    => 'required|url',
            'social_media_id.*' => 'sometimes|nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $idsToUpdate = collect($request->social_media_id)->filter()->all();

            // Update existing entries and collect their IDs
            foreach ($request->social_media as $index => $media) {
                $profileLink   = $request->profile_link[$index] ?? null;
                $socialMediaId = $request->social_media_id[$index] ?? null;

                if ($media && $profileLink) {
                    $socialMedia = $socialMediaId ? SocialMedia::find($socialMediaId) : new SocialMedia();
                    $socialMedia->social_media = $media;
                    $socialMedia->profile_link = $profileLink;
                    $socialMedia->save();

                    // If updating, remove this ID from the $idsToUpdate array
                    if (($key = array_search($socialMediaId, $idsToUpdate)) !== false) {
                        unset($idsToUpdate[$key]);
                    }
                }
            }

            SocialMedia::whereIn('id', $idsToUpdate)->delete();

            return back()->with('t-success', 'Social media links updated successfully.');
        } catch (Exception) {
            return back()->with('t-error', 'Social media links failed update.');
        }
    }

    //__socialMedia delete method__//
    function destroy($id){
        try {
            // Correctly delete the SocialMedia record by ID
            SocialMedia::destroy($id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Social media link deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete social media link.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
