<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $fillable = [
		'begin_date',
		'end_date',
	];

	public function pump_meter_record()
	{
		return $this->hasOne(PumpMeterRecord::class);
	}

	public function getDateAttribute($data)
	{
		return Carbon::create($this->$data)->format("Y-m-d");
	}
}
