<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemUserRequest extends FormRequest
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
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $this->id,
        'phone' => 'nullable|string|max:20',
        'role_id' => 'required|exists:roles,id',
    ];

    if ($this->isMethod('POST')) {
        $rules['password'] = 'required|string|min:6|confirmed';
    }

    return $rules;
}

}
