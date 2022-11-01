<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(TaskController::class)->group(function () {
    Route::get('/tasks', 'index');
    Route::post('/tasks', 'store');
    Route::get('/tasks/{task}', 'show');
    Route::put('/tasks/{task}', 'update');
    Route::delete('/tasks/{task}', 'destroy');
});


//Route::get('/tasks', [TaskController::class, 'index']);
//Route::post('/tasks', [TaskController::class, 'store']);
//Route::get('/tasks/{task}', [TaskController::class, 'show']);
//Route::put('/tasks/{task}', [TaskController::class, 'update']);
//Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

