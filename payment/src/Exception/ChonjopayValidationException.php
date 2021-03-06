<?php

namespace Mekachonjo\Payment\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ChonjopayValidationException extends Exception
{
	/**
	 * Create a new exception instance.
	 *
	 * @param \Illuminate\Contracts\Validation\Validator $validator
	 */
	public function __construct(public $validator)
	{
		//
	}

	public function errors()
	{
		return $this->validator->errors()->messages();
	}

	public function render()
	{
		return response(status: Response::HTTP_OK);
	}
}
