<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

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

Route::get('/', function () {
    return view('welcome');
    
});


Route::prefix('customers')->group(function ()
{
    Route::get('params', [CustomerController::class, 'show']);
    Route::get('index', [CustomerController::class, 'index']);
    Route::get('detail/{invoice}', [CustomerController::class, 'indexDetail']);
    Route::post('params/store', [CustomerController::class, 'store']);
    Route::get('params/{invoice}/position', [CustomerController::class, 'position']);
    Route::get('params/update', [CustomerController::class, 'update']);
    Route::get('params/destroy', [CustomerController::class, 'destroy']);
    Route::get('params/report', [CustomerController::class, 'report']);
    Route::get('params/export', [CustomerController::class, 'export']);
    Route::get('formadd', [CustomerController::class, 'formAdd']);
    Route::get('formedit/{invoice}', [CustomerController::class, 'formEdit']);
    Route::get('formdel/{invoice}', [CustomerController::class, 'formDel']);
    
});


