<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;



Route::post('/contact',[ContactController::class,'store']);
Route::get('/contact',[ContactController::class,'index']);
Route::get('/contact/{contact}',[ContactController::class,'show']);
Route::post('/contact/{contact}/send-mail',[ContactController::class,'sendMail']);