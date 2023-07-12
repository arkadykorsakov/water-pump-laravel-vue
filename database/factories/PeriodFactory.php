<?php

namespace Database\Factories;

use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Period>
 */
class PeriodFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$date_default =  $this->faker->unique()->date('2023-m-01');
		$begin_date = $date_default . ' ' . $this->faker->time('00:00:00');
		$end_date = date('Y-m-d', strtotime('- 1 day', strtotime("+1 month", strtotime($date_default)))) . ' ' . $this->faker->time('23:59:59');
		return [
			'begin_date' => $begin_date,
			'end_date' =>  $end_date,
		];
	}
}
