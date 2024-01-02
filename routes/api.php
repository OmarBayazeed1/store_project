<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\OrderController;
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
Route::get('/searchCategories',[CategoryController::class,'searchByCategoryTitle']);


//medications Routes
Route::get('/medications',[MedicationController::class,'index']);
Route::post('/medications',[MedicationController::class,'store']);
Route::get('/medications/{id}', [MedicationController::class, 'show']);
Route::get('/searchMedications',[MedicationController::class,'searchByMedicationName']);
//getMedByCategories
Route::get('medsByType' , [ MedicationController::class , 'medsByType']);


//orders
//Route::post('/order',[OrderController::class,'storeOrderByUser']);
Route::delete('/order/{id}',[OrderController::class,'destroy']);
Route::get('/order/user/{id}',[OrderController::class,'getByUser']);
Route::get('/order',[OrderController::class,'index']);
    //storeOrder
Route::post('/storeOrder',[OrderController::class,'storeOrder']);

//warehouse
Route::post('/order/payment/{id}',[OrderController::class,'updatePayment']);
Route::post('/order/status/{id}',[OrderController::class,'updateOrderStatus']);

//favourite
Route::get('/favourites', [FavouriteController::class, 'index']);
Route::post('/addFavourite', [FavouriteController::class, 'favourite']);
Route::post('/unFavourite', [FavouriteController::class, 'unFavourite']);
Route::post('/like',[FavouriteController::class,'like']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

