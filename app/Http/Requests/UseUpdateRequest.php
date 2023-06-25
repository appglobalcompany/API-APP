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
            'first_name' => ['nullable', 'string', 'regex:/^[\p{Arabic}A-Za-z]+$/u'],
            'last_name' => ['nullable', 'string', 'regex:/^[\p{Arabic}A-Za-z]+$/u'],
            'phone' => ['nullable', 'numeric'],
            'gender' => ['nullable', 'string', Rule::in(['mela', 'female'])],
            'birthdate' => ['nullable', 'string', 'date'],
            'avatar' => ['nullable', 'string'],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore(auth()->id())],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }
}
