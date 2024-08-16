<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Chat\ChatMessageController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\SectionsController;
use App\Http\Controllers\Api\Commercial\Prices\Fia\PricesFiaController;
use App\Http\Controllers\Api\Commercial\Prices\Infrima\PricesInfrimaController;
use App\Http\Controllers\Api\Commercial\Prices\PricesCommonsController;
use App\Http\Controllers\Api\Commercial\Prices\Atq\PricesAtqController;
use App\Http\Controllers\Api\Commercial\Prices\Quo\PricesQuoController;
use App\Http\Controllers\Api\Commercial\Prices\Ecuador\PricesEcuadorController;
use App\Http\Controllers\Api\Commercial\CommercialConfigController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', [AuthController::class, 'getUser']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'get']);
        Route::post('store', [UsersController::class, 'store']);
        Route::put('update/{id}', [UsersController::class, 'update']);
        Route::get('roles', [UsersController::class, 'getRoles']);
        Route::delete('delete/{id}', [UsersController::class, 'delete']);
    });
    Route::prefix('roles')->group(function () {
        Route::get('/', [RolesController::class, 'get']);
        Route::post('store', [RolesController::class, 'store']);
        Route::put('update/{id}', [RolesController::class, 'update']);
    });
    Route::prefix('sections')->group(function () {
        Route::get('/', [SectionsController::class, 'get']);
        Route::get('recursive', [SectionsController::class, 'getRecursive']);
        Route::post('store-access', [SectionsController::class, 'storeAccess']);
    });
    Route::prefix('commercial')->group(function () {
        Route::prefix('prices')->group(function () {
            Route::prefix('commons')->group(function () {
                Route::post('refresh-data', [PricesCommonsController::class, 'refreshData']);
                Route::get('last-refresh', [PricesCommonsController::class, 'getLastRefresh']);
                Route::get('get-option-list-prices-selected-by-user', [PricesCommonsController::class, 'getOptionListPricesSelectedByUser']);
                Route::post('store-option-list-prices-selected-by-user', [PricesCommonsController::class, 'storeOptionListPricesSelectedByUser']);
            });
            Route::prefix('fia')->group(function () {
                Route::get('/', [PricesFiaController::class, 'get']);
                Route::post('export-to-excel', [PricesFiaController::class, 'exportToExcel']);
                Route::get('list-prices', [PricesFiaController::class, 'getListPrices']);
                Route::post('store-list-prices-public', [PricesFiaController::class, 'storeListPricesPublic']);
            });
            Route::prefix('inf')->group(function () {
                Route::get('/', [PricesInfrimaController::class, 'get']);
                Route::post('export-to-excel', [PricesInfrimaController::class, 'exportToExcel']);
                Route::get('list-prices', [PricesInfrimaController::class, 'getListPrices']);
                Route::post('store-list-prices-public', [PricesInfrimaController::class, 'storeListPricesPublic']);
            });
            Route::prefix('atq')->group(function () {
                Route::get('/', [PricesAtqController::class, 'get']);
                Route::post('export-to-excel', [PricesAtqController::class, 'exportToExcel']);
                Route::get('list-prices', [PricesAtqController::class, 'getListPrices']);
                Route::post('store-list-prices-public', [PricesAtqController::class, 'storeListPricesPublic']);
            });
            Route::prefix('quo')->group(function () {
                Route::get('/', [PricesQuoController::class, 'get']);
                Route::post('export-to-excel', [PricesQuoController::class, 'exportToExcel']);
                Route::get('list-prices', [PricesQuoController::class, 'getListPrices']);
                Route::post('store-list-prices-public', [PricesQuoController::class, 'storeListPricesPublic']);
            });
            Route::prefix('ecuador')->group(function () {
                Route::get('/', [PricesEcuadorController::class, 'get']);
                Route::post('export-to-excel', [PricesEcuadorController::class, 'exportToExcel']);
            });
        });
        Route::prefix('config')->group(function () {
            Route::get('companies', [CommercialConfigController::class, 'getCompanies']);
            Route::get('list-prices', [CommercialConfigController::class, 'getLisPrices']);
            Route::get('user-list-prices', [CommercialConfigController::class, 'getUserListPrices']);
        });
    });
});
