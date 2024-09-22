<?php

use App\Http\Controllers\Backend\AdminController;
use Illuminate\Support\Facades\Route;

//Mail settings routes

Route::post('/mail/update', [AdminController::class,'mailSettingUpdate'])->name('mail.update');

Route::get('/index', [AdminController::class,'index'])->name('backend.index');
