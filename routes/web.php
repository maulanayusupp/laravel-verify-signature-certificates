<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XmlSignatureController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [XmlSignatureController::class, 'verify']);

