<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XmlSignatureController;

Route::post('/verify', [XmlSignatureController::class, 'verify']);

