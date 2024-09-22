<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Web\SystemSettingController;




Route::get('/dashboard', function () {
    return view('backend.app');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/system-setting', [SystemSettingController::class,'index'])->name('system.setting');
Route::post('/system-setting', [SystemSettingController::class,'update'])->name('system.update');

Route::get('/system/profile',[SystemSettingController::class,'profileindex'])->name('profilesetting');

// Route::get('/system/profile',[SystemSettingController::class,'profileindex'])->name('update.profile');



require __DIR__.'/auth.php';
