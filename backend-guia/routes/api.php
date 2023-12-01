<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\GuiaSearchController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/guias', [GuiaController::class, 'index']);
Route::post('/guias', [GuiaController::class, 'create']);
Route::get('/guias/{id}', [GuiaController::class, 'show']);
Route::put('/guias/{id}', [GuiaController::class, 'update']);
Route::delete('/guias/{id}', [GuiaController::class, 'destroy']);

Route::get('/facturas', [FacturaController::class, 'index']);
Route::post('/facturas', [FacturaController::class, 'create']);
Route::get('/facturas/{id}', [FacturaController::class, 'show']);
Route::put('/facturas/{id}', [FacturaController::class, 'update']);
Route::delete('/facturas/{id}', [FacturaController::class, 'destroy']);
Route::get('/factura/count', [FacturaController::class, 'countFacturas']);
Route::post('/factura/costo/', [FacturaController::class, 'calcularTotal']);

Route::get('/pagos', [PagosController::class, 'index']);
Route::post('/pagos', [PagosController::class, 'create']);
Route::get('/pagos/{id}', [PagosController::class, 'show']);
Route::put('/pagos/{id}', [PagosController::class, 'update']);
Route::delete('/pagos/{id}', [PagosController::class, 'destroy']);

// to search
Route::get('/buscar/{texto}', [GuiaSearchController::class, 'search']);