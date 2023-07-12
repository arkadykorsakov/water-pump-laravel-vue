<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rate\StoreRequest;
use App\Http\Requests\Rate\UpdateRequest;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RateController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		try {
			$rates = Rate::all();
			$rates = RateResource::collection($rates);
			return response()->json(['rates' => $rates]);
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
			$rate = Rate::create($data);
			$rate = new RateResource($rate);
			return response()->json(['rate' => $rate], 201);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Rate $rate)
	{
		try {
			$rate = new RateResource($rate);
			return response()->json(['rate' => $rate]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateRequest $request, Rate $rate)
	{
		try {
			$data = $request->validated();
			$rate->update($data);
			$rate = $rate->fresh();
			$rate = new RateResource($rate);
			return response()->json(['rate' => $rate]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Rate $rate)
	{
		try {
			$rate->delete();
			return response()->json([]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}
}
