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

Route::get('/api/auth/callback', [AuthController::class, 'callback']);

Route::get('/api/auth', [AuthController::class, 'auth']);

Route::post('/api/webhooks', [WebhookController::class, 'index']);

Route::middleware(['shopify.auth'])->group(function() {
    Route::get('/api/products/count', [ProductController::class, 'count']);
    Route::get('/api/products/create', [ProductController::class, 'create']);
});

Route::fallback([Controller::class, 'index'])->middleware('shopify.installed');
