<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SystemSettingController extends Controller
{
    //__stripeindex method__//
    public function stripeindex()
    {
        return view('backend.layouts.system_setting.stripe.index');
    }

    //__stripeStore method__//
    public function stripeStore(Request $request)
    {
        $request->validate([
            'stripe_key'    => 'required|string',
            'stripe_secret' => 'required|string',
        ]);
        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak = "\n";
            $envContent = preg_replace([
                '/STRIPE_KEY=(.*)\s/',
                '/STRIPE_SECRET=(.*)\s/',
            ], [
                'STRIPE_KEY=' . $request->stripe_key . $lineBreak,
                'STRIPE_SECRET=' . $request->stripe_secret . $lineBreak,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }
            return redirect()->back()->with('t-success', 'Stripe Setting Update successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('t-error', 'Stripe Setting Update Failed');
        }
        return redirect()->back();
    }
}
