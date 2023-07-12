<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'fio' => $this->faker->name(),
			'area' => $this->faker->randomFloat(2, 0.01, 999.99),
			'start_date' => $this->faker->date('Y-m-d') . ' ' . $this->faker->time('H:i:s')
		];
	}
}
