<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Web\AwardController;
use App\Http\Controllers\Backend\Web\BrachController;
use App\Http\Controllers\Backend\Web\ContactsController;
use App\Http\Controllers\Backend\Web\CompaniesController;
use App\Http\Controllers\Backend\Web\FeaturejobController;
use App\Http\Controllers\Backend\Web\FindtalentController;
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
Route::get('/company/status/{id}',[CompaniesController::class,'companystatus'])->middleware(['auth', 'verified'])->name('company.status');
Route::delete('/company/delete/{id}',[CompaniesController::class,'companydelete'])->middleware(['auth', 'verified'])->name('company.delete');


Route::get('/award-show',[AwardController::class,'allaward'])->middleware(['auth', 'verified'])->name('companyaward.index');
Route::get('/companyaward/create',[AwardController::class,'companyawardcreate'])->middleware(['auth', 'verified'])->name('companyaward.create');
Route::post('/companyaward/create',[AwardController::class,'companyawardstore'])->middleware(['auth', 'verified'])->name('companyaward.store');
Route::get('/companyaward/update/{id}',[AwardController::class,'companyawardedit'])->middleware(['auth', 'verified'])->name('companyaward.edit');
Route::patch('/companyaward/update/{id}',[AwardController::class,'companyawardupdate'])->middleware(['auth', 'verified'])->name('companyaward.update');
Route::delete('/companyaward/delete/{id}',[AwardController::class,'companyawarddelete'])->middleware(['auth', 'verified'])->name('companyaward.delete');


Route::get('/branch-show',[BrachController::class,'allbranch'])->middleware(['auth', 'verified'])->name('companybrach.index');
Route::get('/companybranch/create',[BrachController::class,'companybranchcreate'])->middleware(['auth', 'verified'])->name('companybranch.create');
Route::post('/companybranch/create',[BrachController::class,'companybranchstore'])->middleware(['auth', 'verified'])->name('companybranch.store');
Route::get('/companybranch/update/{id}',[BrachController::class,'companybranchedit'])->middleware(['auth', 'verified'])->name('companybranch.edit');
Route::patch('/companybranch/update/{id}',[BrachController::class,'companybranchupdate'])->middleware(['auth', 'verified'])->name('companybranch.update');
Route::get('/companybranch/status/{id}',[BrachController::class,'companybranchstatus'])->middleware(['auth', 'verified'])->name('companybranch.status');
Route::delete('/companybranch/delete/{id}',[BrachController::class,'companybranchdelete'])->middleware(['auth', 'verified'])->name('companybranch.delete');



Route::get('/feature-show',[FeaturejobController::class,'allfeature'])->middleware(['auth', 'verified'])->name('featurejob.index');
Route::get('/featurejob/create',[FeaturejobController::class,'featurejobcreate'])->middleware(['auth', 'verified'])->name('featurejob.create');
Route::post('/featurejob/create',[FeaturejobController::class,'featurejobstore'])->middleware(['auth', 'verified'])->name('featurejob.store');
Route::get('/featurejob/update/{id}',[FeaturejobController::class,'featurejobedit'])->middleware(['auth', 'verified'])->name('featurejob.edit');
Route::patch('/featurejob/update/{id}',[FeaturejobController::class,'featurejobupdate'])->middleware(['auth', 'verified'])->name('featurejob.update');
Route::get('/featurejob/status/{id}',[FeaturejobController::class,'featurejobstatus'])->middleware(['auth', 'verified'])->name('featurejob.status');
Route::delete('/featurejob/delete/{id}',[FeaturejobController::class,'featurejobdelete'])->middleware(['auth', 'verified'])->name('featurejob.delete');



Route::get('/contacts-show',[ContactsController::class,'allcontacts'])->middleware(['auth', 'verified'])->name('contacts.index');
Route::get('/contacts/create',[ContactsController::class,'contactcreate'])->middleware(['auth', 'verified'])->name('contacts.create');
Route::post('/contacts/create',[ContactsController::class,'contactstore'])->middleware(['auth', 'verified'])->name('contacts.store');
Route::delete('/contacts/delete/{id}',[ContactsController::class,'contactdelete'])->middleware(['auth', 'verified'])->name('contacts.delete');


Route::get('/talent-index', [FindtalentController::class,'talentindex'])->middleware(['auth', 'verified'])->name('talent.index');
Route::post('/talent-update', [FindtalentController::class,'talentupdate'])->middleware(['auth', 'verified'])->name('talent.update');


require __DIR__.'/auth.php';
