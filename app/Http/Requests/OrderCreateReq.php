<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderCreateReq extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'required', 'string','regex:/^\+201[0125]\d{8}$/',
            'address' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Phone number must start with +20 and be a valid Egyptian mobile number.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'vaildation errors',
            'errors' => $validator->errors(),
        ] , 433));
    }
}
