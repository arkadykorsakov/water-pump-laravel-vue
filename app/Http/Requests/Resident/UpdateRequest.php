<?php

namespace App\Http\Requests\Resident;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'fio' => 'string|required',
			'area' => 'numeric|between:0,99999999.99|required',
			"start_date" => "required|date_format:Y-m-d\TH:i",
		];
	}

	public function messages(): array
	{
		return [
			'string' => 'Строковое поле',
			'required' => 'Обязательное поле',
			'area.numeric' => 'Числовое поле (0.00)',
			'area.between' => 'Превышен диапазон вводимых значений (0 - 99999999.99)',
			'date_format' => 'Невалидная дата'
		];
	}
}
