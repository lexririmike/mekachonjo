<?php

namespace Mekachonjo\Payment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Mekachonjo\Payment\Exceptions\ChonjopayValidationException;

class Mekaconnect extends FormRequest
{
	public function failedValidation(Validator $validator)
	{
		throw new ChonjoPayValidationException($validator);
	}
}