<?php

namespace App\Http\Controllers;

use App\Http\Requests\Resident\StoreRequest;
use App\Http\Requests\Resident\UpdateRequest;
use App\Http\Resources\ResidentResource;
use App\Models\Resident;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResidentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		try {
			$residents = Resident::all();
			$residents = ResidentResource::collection($residents);
			return response()->json(['residents' => $residents]);
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
			$resident = Resident::create($data);
			$resident = new ResidentResource($resident);
			return response()->json(['resident' => $resident], 201);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Resident $resident)
	{
		try {
			$resident = new ResidentResource($resident);
			return response()->json(['resident' => $resident]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateRequest $request, Resident $resident)
	{
		try {
			$data = $request->validated();
			$resident->update($data);
			$resident = $resident->fresh();
			$resident = new ResidentResource($resident);
			return response()->json(['resident' => $resident]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Resident $resident)
	{
		try {
			if (count($resident->bills)) {
				return response()->json(['message' => 'Данному дачнику уже выставлены счета. Нельзя удалить!'], 409);
			}
			$resident->delete();
			return response()->json([]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}
}
