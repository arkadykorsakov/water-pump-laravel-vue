<?php

namespace App\Http\Requests\Rate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
			'period_id' => ['required', 'integer', 'exists:periods,id', Rule::unique('rates', 'period_id')->ignore($this->rate)],
			'price' => 'numeric|between:0,999999.99|required'
		];
	}

	public function messages(): array
	{
		return [
			'string' => 'Строковое поле',
			'required' => 'Обязательное поле',
			'period_id.exists' => 'Данного периода не существует',
			'period_id.unique' => 'Данная запись уже существует',
			'price.numeric' => 'Числовое поле (0.00)',
			'price.between' => 'Превышен диапазон вводимых значений (0 - 999999.99)',
		];
	}
}
