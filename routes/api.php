<?php

use App\Http\Controllers\SendDataBerasController;
use App\Models\DataBeras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/data-beras', function () {
    $data = DataBeras::with('beras')->get();
    return response()->json($data);
});

Route::post('/evaluasi-model', [SendDataBerasController::class, 'storeEvaluasi']);
Route::post('/prediksi-bulanan', [SendDataBerasController::class, 'storePrediksiBulanan']);
Route::post('/prediksi', [SendDataBerasController::class, 'storeData']);
