<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProblemSolvingController;
use App\Http\Controllers\RestaurantController;

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Part One: Problem Solving
Route::get('numbers-without-5', [ProblemSolvingController::class, 'numbers_without_5']);
Route::get('index-of-string', [ProblemSolvingController::class, 'index_of_string']);
Route::get('calculate-steps', [ProblemSolvingController::class, 'calculate_steps']);


// Part Two
Route::prefix('restaurant')->group(function () {
    Route::post('/make-order', [RestaurantController::class, 'make_order']);

    Route::get('/order/{order_id}', [RestaurantController::class, 'get_order']);

    Route::get('/get-menu', [RestaurantController::class, 'get_menu']);
});
