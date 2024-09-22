<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\DynamicpageController;
use App\Http\Controllers\Backend\SocialmediaController;
use App\Http\Controllers\Backend\SystemSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/login',[AdminController::class, 'admin_login'])->name('admin.login');
Route::get('/dashboard',[AdminController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

//stripe routes---------
Route::controller(SystemSettingController::class)->group(function () {
    Route::get('/stripe', 'stripeindex')->middleware(['auth', 'verified'])->name('stripe.index');
    Route::post('/stripe/store', 'stripeStore')->name('stripe.store');

});

//pages rotes---------
Route::controller(DynamicpageController::class)->group(function () {
    Route::get('/pages/index', 'pageindex')->name('dynamic_page.index');
    Route::get('/pages/create', 'page_create')->name('dynamic_page.create');
    Route::post('/pages/store', 'page_store')->name('dynamic_page.store');
    Route::get('/dynamic-page/edit/{id}', 'page_edit')->name('dynamic_page.edit');
    Route::post('/dynamic-page/update/{id}', 'page_update')->name('dynamic_page.update');
    Route::get('/dynamic-page/status/{id}',  'status')->name('dynamic_page.status');
    Route::delete('/dynamic-page/delete/{id}', 'dynamicPageDelete')->name('dynamic_page.delete');
})->middleware(['auth', 'verified']);

//Social Media routes---------
Route::controller(SocialmediaController::class)->group(function () {
    Route::get('/social-media', 'socialMedia')->name('social_media.index');
    Route::post('/system/social', 'update')->name('socialmedia.update');
    Route::delete('/system/social/{id}', 'destroy')->name('socialmedia.delete');
})->middleware(['auth', 'verified']);
