<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillResource;
use App\Models\Bill;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BillController extends Controller
{
	public function index()
	{
		try {
			$bills = Bill::all();
			$bills = BillResource::collection($bills);
			return response()->json(['bills' => $bills]);
		} catch (\Exception $e) {
			throw new HttpException(500, '#500 Ошибка сервера');
		}
	}
}
