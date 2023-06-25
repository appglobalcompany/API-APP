<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UseRegisterrRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'regex:/^[\p{Arabic}A-Za-z]+$/u'],
            'last_name' => ['required', 'string', 'regex:/^[\p{Arabic}A-Za-z]+$/u'],
            'phone' => ['required', 'numeric'],
            'gender' => ['required', 'string', Rule::in(['mela', 'female'])],
            'birthdate' => ['required', 'string', 'date'],
            'avatar' => ['nullable', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
