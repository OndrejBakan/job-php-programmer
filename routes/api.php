<?php

use App\Http\Controllers\Api\V1\CustomerController;
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

Route::group(['prefix' => '/v1', 'as' => 'api.v1.'], function () {
    Route::apiResource('/customers', CustomerController::class);
    Route::match(['PUT', 'PATCH'], '/customers/{customer}/customer-groups', [CustomerController::class, 'syncCustomerGroups'])->name('customers.customer-groups.update');
    Route::post('/customers/{customer}/customer-groups', [CustomerController::class, 'attachCustomerGroups'])->name('customers.customer-groups.create');
    Route::delete('/customers/{customer}/customer-groups', [CustomerController::class, 'detachCustomerGroups'])->name('customers.customer-groups.destroy');
});
