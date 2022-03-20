<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\SmsProvider;
use App\Models\Message;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SmsProviderController;
use App\Http\Controllers\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::apiResource('messages', MessageController::class)->except('put', 'delete);

Route::get('/messages', [MessageController::class, 'index']);
Route::post('/messages', [MessageController::class, 'store']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}/provider', [ProductController::class, 'updateProvider']);

Route::apiResource('users', UserController::class)->only('index', 'show');

Route::apiResource('smsproviders', SmsProviderController::class)->only('index');
