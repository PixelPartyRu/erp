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

Route::group(['middleware' => ['web']], function () {
	Route::get('/', 'HomeController@index');
	Route::resource('client', 'ClientController');
	#Route::post('debtor/filter', 'DebtorController@filter');
	Route::get('client/{id}/agreement','ClientController@agreement');
	Route::resource('debtor', 'DebtorController');
	Route::resource('relation', 'RelationController');
	Route::post('relation/getFilterData', 'RelationController@getFilterData');
	Route::resource('agreement', 'AgreementController');
	Route::resource('tariff', 'TariffController');
	Route::resource('limit', 'LimitController');
	Route::resource('commission', 'CommissionController',['except' => ['show','index']]);
	Route::resource('commissions_rage', 'CommissionRageController',['except' => ['show','index']]);
	Route::get('activateTariff/{id}', 'TariffController@activateTariff');
	Route::get('relationsByClient/{id}','LimitController@relationsByClient');

	Route::get('delivery/getDescription', 'DeliveryController@getDescription');
	Route::get('delivery/verification', 'DeliveryController@verification');
	Route::get('delivery/getFilterData', 'DeliveryController@getFilterData');
	Route::post('delivery/deliveryDelete', 'DeliveryController@deliveryDelete');
	Route::post('delivery/getPopapDelivery', 'DeliveryController@getPopapDelivery');
	Route::resource('delivery', 'DeliveryController');

	Route::resource('finance', 'FinanceController');
	Route::post('finance/financingSuccess', 'FinanceController@financingSuccess');
	Route::post('finance/getSum', 'FinanceController@getSum');
	Route::post('finance/getDeliveries', 'FinanceController@getDeliveries');
	Route::post('finance/getFinances', 'FinanceController@getFinances');
	Route::post('finance/filter', 'FinanceController@filter');

	Route::get('commission/{type}', 'CommissionController@commissionType');
	Route::get('commission/lastTariffId', 'CommissionController@lastTariffId');
	Route::get('commission/putAllCommissionsForTariff/{id}', 'CommissionController@putAllCommissionsForTariff');

	Route::resource('chargeCommission', 'ChargeCommissionController');
	Route::post('chargeCommission/recalculationTest', 'ChargeCommissionController@recalculationTest');
	Route::post('chargeCommission/getFilterData', 'ChargeCommissionController@getFilterData');

	Route::resource('repayment', 'RepaymentController');
	Route::post('repayment/getImportFile', 'RepaymentController@getImportFile');
	Route::post('repayment/getRepayment', 'RepaymentController@getRepayment');
	Route::post('repayment/createStore', 'RepaymentController@createStore');
	Route::post('repayment/getDelivery', 'RepaymentController@getDelivery');
	Route::post('repayment/repayment', 'RepaymentController@repayment');
	Route::post('repayment/getDeliveryFirstPayment', 'RepaymentController@getDeliveryFirstPayment');
	Route::post('repayment/updateBalance', 'RepaymentController@updateBalance');
	Route::post('repayment/getCommissionData', 'RepaymentController@getCommissionData');
	Route::post('repayment/getWaybillAmount', 'RepaymentController@getWaybillAmount');
	Route::post('repayment/getIndexRepayment', 'RepaymentController@getIndexRepayment');
	Route::post('repayment/deleteRepayment', 'RepaymentController@deleteRepayment');
	Route::post('repayment/deleteConfirm', 'RepaymentController@deleteConfirm');
	Route::post('repayment/ClearTable', 'RepaymentController@ClearTable');
	Route::post('repayment/getDebtor', 'RepaymentController@getDebtor');
	
	Route::resource('reportRepayment', 'ReportRepaymentController');
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
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'HomeController@index');
});
Route::resource('invoicing','InvoicingController');
Route::get('recalculation', 'NightChargeController@index');
Route::post('recalculation/recalculate', 'NightChargeController@recalculate');
Route::post('excelCreate', 'ExcelController@index');
Route::get('new', function ()    {
    return view('new');
});

