<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Rules\PhoneValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $input['phone'] = $input['full_phone'];
    
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],

            'phone' => ['required' , 'unique:users,phone' , new PhoneValidationRule()],

            'address' => ['required' , 'string'],

            'city_id' => ['required' , 'exists:cities,id'],
            
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_name' => 'customer',
            'city_id' => $input['city_id'],
            'phone' => $input['phone'],
            'address' => $input['address'],
        ]);
    }
}
