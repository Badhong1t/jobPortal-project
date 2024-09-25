<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\backend\cms\CountryController;
use Illuminate\Support\Facades\Route;

//Mail settings routes

Route::post('/mail/update', [AdminController::class,'mailSettingUpdate'])->name('mail.update');

Route::get('/mail/view', [AdminController::class,'mailView'])->name('backend.mail.view');

//Social media settings routes

Route::get('/socialMediaView', [AdminController::class,'socialMediaView'])->name('backend.socialMedia.view');

Route::post('/socialMedia/update', [AdminController::class,'socialMediaUpdate'])->name('socialMedia.update');

Route::delete('/socialMedia/delete/{id}', [AdminController::class,'destroy'])->name('socialMedia.delete');

//country route

Route::controller(CountryController::class)->group(function (){

    Route::get('/country/view', 'index')->name('backend.country.index');
    Route::post('/country/store/{id}',  'store')->name('backend.country.store');
    Route::get('/country/create/view',  'create')->name('backend.country.create');
    Route::get('/country/edit/view/{id}', 'edit')->name('backend.country.edit');
    Route::post('/country/update/{id}', 'update')->name('backend.country.update');
    Route::delete('/country/delete/{id}', 'destroy')->name('backend.country.delete');

});
