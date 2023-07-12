<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PumpMeterRecordResource;


class PeriodResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'begin_date' => $this->begin_date,
			'end_date' => $this->end_date,
			'pump_meter_record' => $this->pump_meter_record

		];
	}
}
