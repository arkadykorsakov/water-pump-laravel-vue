<?php

namespace App\Http\Requests\Period;

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
			"begin_date" => "required|date_format:Y-m-d",
			"end_date" => "required|date_format:Y-m-d",
		];
	}

	public function messages(): array
	{
		return [
			'date_format' => 'Невалидная дата',
			'required' => 'Обязательное поле',
		];
	}
}
