<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::resource('client', 'ClientController');
Route::get('client/{id}/agreement','ClientController@agreement');

Route::resource('debtor', 'DebtorController');
Route::resource('relation', 'RelationController');
Route::resource('agreement', 'AgreementController');
Route::resource('tariff', 'TariffController');
Route::resource('commission', 'CommissionController',['except' => ['show','index']]);
Route::get('activateTariff/{id}', 'TariffController@activateTariff');

Route::post('delivery/getDescription', 'DeliveryController@getDescription');
Route::post('delivery/verification', 'DeliveryController@verification');
Route::post('delivery/getFilterData', 'DeliveryController@getFilterData');
Route::resource('delivery', 'DeliveryController');

Route::resource('finance', 'FinanceController');
Route::post('finance/financingSuccess', 'FinanceController@financingSuccess');
Route::post('finance/getSum', 'FinanceController@getSum');
Route::post('finance/getDeliveries', 'FinanceController@getDeliveries');
Route::post('finance/filter', 'FinanceController@filter');

Route::get('commission/{type}', 'CommissionController@commissionType');
Route::get('commission/lastTariffId', 'CommissionController@lastTariffId');
Route::get('commission/putAllCommissionsForTariff/{id}', 'CommissionController@putAllCommissionsForTariff');

Route::resource('chargeCommission', 'ChargeCommissionController');
Route::post('chargeCommission/recalculationTest', 'ChargeCommissionController@recalculationTest');

Route::resource('repayment', 'RepaymentController');
Route::post('repayment/getImportFile', 'RepaymentController@getImportFile');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
