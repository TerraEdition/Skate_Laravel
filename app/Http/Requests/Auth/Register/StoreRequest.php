<?php

namespace App\Http\Requests\Auth\Register;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', Rule::unique('users')],
            'name' => 'required',
            'password' => 'required|same:confirmation_password',
            'confirmation_password' => 'required|same:password',
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'confirmation_password' => 'konfirmasi password',
        ];
    }
}
