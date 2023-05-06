<?php

use App\Http\Controllers\Api\ClientRequestsController;
use App\Http\Controllers\api\HomeController;
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

Route::middleware('auth.api')->group(function () {
    Route::get('requests', [ClientRequestsController::class, 'index'])->name('clientRequests.index');
    Route::get('requests/{clientRequests}', [ClientRequestsController::class, 'show'])->name('clientRequests.show');
    Route::match(['put'], 'requests/{clientRequests}', [ClientRequestsController::class, 'update'])->name('clientRequests.update');
    Route::delete('requests/{clientRequests}', [ClientRequestsController::class, 'destroy'])->name('clientRequests.destroy');
});

Route::post('requests', [ClientRequestsController::class, 'store'])->name('clientRequests.store');


