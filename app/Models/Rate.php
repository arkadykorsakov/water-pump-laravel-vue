<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $fillable = [
		'period_id',
		'price',
	];

	public function period()
	{
		return $this->belongsTo(Period::class);
	}
}
