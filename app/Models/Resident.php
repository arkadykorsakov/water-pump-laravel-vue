<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $fillable = [
		'fio',
		'area',
		'start_date',
	];

	public function getDateAttribute()
	{
		return Carbon::create($this->start_date)->format("Y-m-d\TH:i");
	}

	public function bills()
	{
		return $this->hasMany(Bill::class);;
	}
}
