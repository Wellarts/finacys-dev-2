<?php

use App\Http\Controllers\DeleteParcelasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Route::get('novaParcela/{id}',[ControllerNovaParcela::class, 'novaParcela'])->name('novaParcela');
Route::get('deleteParcelas/{idCompra}',[DeleteParcelasController::class, 'deleteParcelas'])->name('deleteParcelas');
Route::get('deleteDespesas/{idDespesa}',[DeleteParcelasController::class, 'deleteDespesas'])->name('deleteDespesas');
