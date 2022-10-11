<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * @param Validator $validator
     * @return void
     */
    public function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'errors' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);

    }
}
