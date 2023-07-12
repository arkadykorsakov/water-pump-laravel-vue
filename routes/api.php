<?php

use Illuminate\Http\Request;
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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
	Route::group(['prefix' => 'residents'], function () {
		Route::get('/', 'ResidentController@index');
		Route::post('/', 'ResidentController@store');
		Route::get('/{resident}', 'ResidentController@show');
		Route::put('/{resident}', 'ResidentController@update');
		Route::delete('/{resident}', 'ResidentController@destroy');
	});

	Route::group(['prefix' => 'periods'], function () {
		Route::get('/', 'PeriodController@index');
		Route::post('/', 'PeriodController@store');
		Route::get('/{period}', 'PeriodController@show');
		Route::put('/{period}', 'PeriodController@update');
		Route::delete('/{period}', 'PeriodController@destroy');
	});

	Route::group(['prefix' => 'pump_meter_records'], function () {
		Route::get('/', 'PumpMeterRecordController@index');
		Route::post('/', 'PumpMeterRecordController@store');
		Route::get('/{pump_meter_record}', 'PumpMeterRecordController@show');
		Route::put('/{pump_meter_record}', 'PumpMeterRecordController@update');
		Route::delete('/{pump_meter_record}', 'PumpMeterRecordController@destroy');
	});

	Route::group(['prefix' => 'rates'], function () {
		Route::get('/', 'RateController@index');
		Route::post('/', 'RateController@store');
		Route::get('/{rate}', 'RateController@show');
		Route::put('/{rate}', 'RateController@update');
		Route::delete('/{rate}', 'RateController@destroy');
	});

	Route::group(['prefix' => 'bills'], function () {
		Route::get('/', 'BillController@index');
	});
});
