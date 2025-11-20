<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\InstitutionController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('institutions', [InstitutionController::class, 'store']);