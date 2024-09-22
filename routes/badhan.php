<?php

use App\Http\Controllers\Backend\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/login',[AdminController::class, 'admin_login'])->name('admin.login');
Route::get('/dashboard',[AdminController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/mail/update', [AdminController::class,'mailSettingUpdate'])->name('mail.update');

