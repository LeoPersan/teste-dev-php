<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

$missingSupplier = fn() => response()->json(['status' => 'error','message' => 'Supplier not found.'], 404);

Route::get('/suppliers', [SupplierController::class, 'index']);
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->missing($missingSupplier);
Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->missing($missingSupplier);
