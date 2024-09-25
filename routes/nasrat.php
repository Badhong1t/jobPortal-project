<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Web\AwardController;
use App\Http\Controllers\Backend\Web\CompaniesController;
use App\Http\Controllers\Backend\Web\SystemSettingController;



Route::get('/dashboard', function () {
    return view('backend.app');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/system-setting', [SystemSettingController::class,'index'])->middleware(['auth', 'verified'])->name('system.setting');
Route::post('/system-setting', [SystemSettingController::class,'update'])->middleware(['auth', 'verified'])->name('system.update');

Route::get('/system/profile',[SystemSettingController::class,'profileindex'])->middleware(['auth', 'verified'])->name('profilesetting');

// Route::get('/system/profile',[SystemSettingController::class,'profileindex'])->name('update.profile');

Route::get('/companies-show',[CompaniesController::class,'allcompanies'])->middleware(['auth', 'verified'])->name('company.index');
Route::get('/company/create',[CompaniesController::class,'companycreate'])->middleware(['auth', 'verified'])->name('company.create');
Route::post('/company/create',[CompaniesController::class,'companystore'])->middleware(['auth', 'verified'])->name('company.store');
Route::get('/company/update/{id}',[CompaniesController::class,'companyedit'])->middleware(['auth', 'verified'])->name('company.edit');
Route::patch('/company/update/{id}',[CompaniesController::class,'companyupdate'])->middleware(['auth', 'verified'])->name('company.update');
Route::delete('/company/delete/{id}',[CompaniesController::class,'companydelete'])->middleware(['auth', 'verified'])->name('company.delete');


Route::get('/award-show',[AwardController::class,'allaward'])->middleware(['auth', 'verified'])->name('companyaward.index');
Route::get('/companyaward/create',[AwardController::class,'companyawardcreate'])->middleware(['auth', 'verified'])->name('companyaward.create');
Route::post('/companyaward/create',[AwardController::class,'companyawardstore'])->middleware(['auth', 'verified'])->name('companyaward.store');
Route::get('/companyaward/update/{id}',[AwardController::class,'companyawardedit'])->middleware(['auth', 'verified'])->name('companyaward.edit');
Route::patch('/companyaward/update/{id}',[AwardController::class,'companyawardupdate'])->middleware(['auth', 'verified'])->name('companyaward.update');
Route::delete('/companyaward/delete/{id}',[AwardController::class,'companyawarddelete'])->middleware(['auth', 'verified'])->name('companyaward.delete');


require __DIR__.'/auth.php';
