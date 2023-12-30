<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MedicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/logout',[AuthController::class,'logout']);
});

//categories Routes
Route::get('/categories',[CategoryController::class,'index']);
Route::post('/categories',[CategoryController::class,'store']);


//medications Routes
Route::get('/medications',[MedicationController::class,'index']);
Route::post('/medications',[MedicationController::class,'store']);
Route::get('/medications/{id}', [MedicationController::class, 'show']);

//getMedByCategories
Route::get('medsByType' , [ MedicationController::class , 'medsByType']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

