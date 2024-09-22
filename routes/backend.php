<?php

use App\Http\Controllers\Backend\AdminController;
use Illuminate\Support\Facades\Route;

//Mail settings routes

Route::post('/mail/update', [AdminController::class,'mailSettingUpdate'])->name('mail.update');

Route::get('/mail_view', [AdminController::class,'mailView'])->name('backend.mail.view');

//Social media settings routes

Route::get('/socialMediaView', [AdminController::class,'socialMediaView'])->name('backend.socialMedia.view');

Route::post('/update-socialMedia', [AdminController::class,'socialMediaUpdate'])->name('socialMedia.update');

Route::delete('/delete-socialMedia/{id}', [AdminController::class,'destroy'])->name('socialMedia.delete');
