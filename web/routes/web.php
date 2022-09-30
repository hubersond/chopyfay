<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/api/auth', [AuthController::class, 'auth']);

Route::get('/api/auth/callback', [AuthController::class, 'callback']);

Route::post('/api/webhooks', [WebhookController::class, 'index']);

Route::middleware(['shopify.auth'])->group(function() {
    Route::get('/api/products', [ProductController::class, 'all']);
    Route::get('/api/products/count', [ProductController::class, 'count']);
    Route::get('/api/products/create', [ProductController::class, 'create']);
    Route::post('/api/rest/products/create', [ProductController::class, 'createRest']);
});

Route::fallback([Controller::class, 'fallback'])->middleware('shopify.installed');
