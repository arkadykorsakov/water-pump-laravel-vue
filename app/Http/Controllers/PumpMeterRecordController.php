<?php

namespace App\Http\Controllers;

use App\Http\Requests\PumpMeterRecord\StoreRequest;
use App\Http\Requests\PumpMeterRecord\UpdateRequest;
use App\Http\Resources\PumpMeterRecordResource;
use App\Models\Bill;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\Rate;
use App\Models\Resident;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PumpMeterRecordController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		try {
			$pump_meter_records = PumpMeterRecord::all();
			$pump_meter_records = PumpMeterRecordResource::collection($pump_meter_records);
			return response()->json(['pump_meter_records' => $pump_meter_records]);
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
			$residents = Resident::all();

			if (!count($residents)) {
				return response()->json(['message' => 'Дачники не найдены. Нельзя выполнить расчеты!'], 404);
			}

			$data = $request->validated();
			$pump_meter_record = PumpMeterRecord::create($data);

			$totalArea = 0;
			foreach ($residents as $resident) {
				if ($pump_meter_record->period->begin_date > $resident->start_date) {
					$totalArea += $resident->area;
				}
			}

			$rate = Rate::where('period_id', $pump_meter_record->period_id)->first();
			$totalBill = $pump_meter_record->amount_volume * $rate->price;
			foreach ($residents as $resident) {
				if ($pump_meter_record->period->begin_date > $resident->start_date) {
					$perOfTotalBill = round((100 * $resident->area / $totalArea), 2);
					$billResident = round(($totalBill / 100 * $perOfTotalBill), 2);
					Bill::create(['resident_id' => $resident->id,	'period_id' => $pump_meter_record->period_id,	'amount_rub' => $billResident]);
				}
			}

			$pump_meter_record = new PumpMeterRecordResource($pump_meter_record);
			return response()->json(['pump_meter_record' => $pump_meter_record, 'totalArea' => $totalArea, 'totalBill' => $totalBill], 201);
		} catch (\Exception $e) {
			throw new HttpException(500, $e);
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(PumpMeterRecord $pump_meter_record)
	{
		try {
			$pump_meter_record = new PumpMeterRecordResource($pump_meter_record);
			return response()->json(['pump_meter_record' => $pump_meter_record]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateRequest $request, PumpMeterRecord $pump_meter_record)
	{
		try {
			$data = $request->validated();
			$pump_meter_record->update($data);
			$pump_meter_record = $pump_meter_record->fresh();
			$pump_meter_record = new PumpMeterRecordResource($pump_meter_record);
			return response()->json(['pump_meter_record' => $pump_meter_record]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(PumpMeterRecord $pump_meter_record)
	{
		try {
			$bills = Bill::where('period_id', $pump_meter_record->period_id)->get();
			foreach ($bills as $bill) {
				$bill->delete();
			}
			$pump_meter_record->delete();
			return response()->json([]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}
}
