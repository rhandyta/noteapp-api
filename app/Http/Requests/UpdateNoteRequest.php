<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateNoteRequest extends FormRequest
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
            'title' => 'required|min:5|max:150|string',
            'body' => 'required|min:5|string',
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
            'title.required' => 'Title is required',
            'title.min' => 'Title min length 5 characters',
            'body.required' => 'Body is required',
            'body.min' => 'Body min length 5 characters'
        ];
    }
}
