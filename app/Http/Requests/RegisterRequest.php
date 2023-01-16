<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:40',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|min:3'
        ];
    }

    public function failedValidation(Validator $validator) 
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'messages' => $validator->errors(),
            'code' => 400
        ]));
    }

    public function messages() 
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be string',
            'name.min' => "name min length 3 characters",
            'email.required' => 'Email is required',
            'email.email' => 'Email must be in e-mail format',
            'password.required' => 'Password is required',
            'password.min'=> "Password min length  3 characters"
        ];
    }
}
