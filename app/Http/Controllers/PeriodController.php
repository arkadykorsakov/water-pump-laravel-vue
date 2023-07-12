<?php

namespace App\Http\Controllers;

use App\Http\Requests\Period\StoreRequest;
use App\Http\Requests\Period\UpdateRequest;
use App\Http\Resources\PeriodResource;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PeriodController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		try {
			$periods = Period::orderBy('begin_date')->get();
			$periods = PeriodResource::collection($periods);
			return response()->json(['periods' => $periods]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreRequest $request)
	{
		try {
			$data = $request->validated();
			$data['end_date'] = Carbon::create($data['end_date'] . 'T23:59:59')->format('Y-m-d\TH:i:s');
			$period = Period::create($data);
			$period = new PeriodResource($period);
			return response()->json(['period' => $period], 201);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Period $period)
	{
		try {
			$period = new PeriodResource($period);
			return response()->json(['period' => $period]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateRequest $request, Period $period)
	{
		try {
			$data = $request->validated();
			$data['end_date'] = Carbon::create($data['end_date'] . 'T23:59:59')->format('Y-m-d\TH:i:s');
			$period->update($data);
			$period = $period->fresh();
			$period = new PeriodResource($period);
			return response()->json(['period' => $period]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Period $period)
	{
		try {
			if ($period->pump_meter_record) {
				return response()->json(['message' => 'Внесены показания счетчиков. Удалить нельзя!'], 409);
			}
			$period->delete();
			return response()->json([]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}
}
