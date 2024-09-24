<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DynamicpageController;
use App\Http\Controllers\Backend\SocialmediaController;
use App\Http\Controllers\Backend\SystemSettingController;
use App\Http\Controllers\Backend\TagController;
use Illuminate\Support\Facades\Route;

//admin profile routes---------
Route::get('/admin/login',[AdminController::class, 'admin_login'])->name('admin.login');
Route::get('/dashboard',[AdminController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admin/profile/edit',[AdminController::class, 'profile_edit'])->middleware(['auth', 'verified'])->name('admin.profile.edit');
Route::post('/admin/profile/update',[AdminController::class, 'profile_update'])->name('admin.profile.update');

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
    Route::get('/social-media', 'socialMedia')->name('socialmedia.index');
    Route::post('/system/social', 'update')->name('socialmedia.update');
    Route::delete('/system/social/{id}', 'destroy')->name('socialmedia.delete');
})->middleware(['auth', 'verified']);

//tegs routes---------
Route::controller(TagController::class)->group(function () {
    Route::get('tags', 'tag_index')->name('tag.index');
    Route::get('/tags/create', 'tag_create')->name('tag.create');
    Route::post('/tags/store', 'tag_store')->name('tag.store');
    Route::get('/tags/edit/{id}', 'tag_edit')->name('tag.edit');
    Route::post('/tags/update/{id}', 'tag_update')->name('tag.update');
    Route::delete('/tags/delete/{id}', 'destroy')->name('tag.delete');
})->middleware(['auth', 'verified']);

//blog routes---------
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog/index', 'blog_index')->name('blog.index');
    Route::get('/blog/create', 'blog_create')->name('blog.create');
    Route::post('/blog/store', 'blog_store')->name('blog.store');
    Route::get('/blog/post/edit/{id}', 'blog_post_edit')->name('blog.post.edit');
    Route::post('/blog/update/{id}', 'blog_update')->name('blog.update');
    Route::get('/blog/status/update/{id}', 'blog_status_update')->name('blog.status.update');
    Route::delete('/blog/delete/{id}', 'blog_delete')->name('blog.delete');
})->middleware(['auth', 'verified']);


//category routes---------
Route::controller(CategoryController::class)->group(function () {
    Route::get('/category/index', 'category_index')->name('category.index');
    Route::get('/category/create','category_create')->name('category.create');
    Route::post('/category/store', 'category_store')->name('category.store');
    Route::get('/category/edit/{id}', 'category_edit')->name('category.edit');
    Route::post('/category/update/{id}', 'category_update')->name('category.update');
    Route::delete('/category/delete/{id}','category_delete')->name('category.delete');
    Route::get('/category/status/update/{id}', 'category_status_update')->name('category.status.update');
})->middleware(['auth', 'verified']);
