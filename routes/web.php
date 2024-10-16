<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XmlSignatureController;


Route::get('/welcome', function () {
    return view('welcome');
});

Route::post('/', [XmlSignatureController::class, 'verify']);

