<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\PhoneValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderCreateReq extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
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
            'phone' => ['required', 'string' , new PhoneValidationRule()],
            'address' => 'nullable|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'delivery_option' => 'sometimes|in:proceed,discuss',
            'payment_method' => 'required|string',
            'insta_account' => [Rule::requiredIf(fn() => $this->input('payment_method') === 'instapay') , 'string' , 'max:50']
        ];
    }

    protected function prepareForValidation()
    {
       $this->merge(['delivery_option' => is_null($this->input('delivery_option')) ? 'proceed' : $this->input('delivery_option')]);    
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
