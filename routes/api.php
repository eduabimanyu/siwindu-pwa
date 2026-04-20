<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\EwaletController;

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

// Authentication routes (public)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Transactions
    Route::get('/transactions', [TransaksiController::class, 'data']);
    Route::get('/transactions/top-products', [TransaksiController::class, 'getTopProducts']);
    Route::get('/transactions/{id}', [TransaksiController::class, 'detail']);
    Route::get('/transactions/{id}/thermal', [TransaksiController::class, 'getThermalPrintData']);
    Route::post('/transactions', [TransaksiController::class, 'store']);

    // Shifts
    Route::get('/shifts/current', [ShiftController::class, 'getCurrentShift']);
    Route::get('/shifts/history', [ShiftController::class, 'getShiftHistory']);
    Route::get('/shifts/{id}', [ShiftController::class, 'shiftdetail']);
    Route::get('/shifts/{id}/thermal', [ShiftController::class, 'getThermalPrintData']);
    Route::get('/shifts/{id}/pdf', [ShiftController::class, 'generatePDF']);
    Route::post('/shifts/start', [ShiftController::class, 'store']);
    Route::post('/shifts/end', [ShiftController::class, 'update']);

    // Master Data
    Route::get('/items', [ItemController::class, 'data']);
    Route::get('/kategori', [KategoriController::class, 'data']);
    Route::get('/wisata', [WisataController::class, 'index']);
    Route::get('/banks', [BankController::class, 'getbank']);
    Route::get('/ewalets', [EwaletController::class, 'getewalet']);
});
