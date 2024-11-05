<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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

        $user = Auth::user();

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'username' => "required|string|max:255|unique:users,username,$user->id",
            'contact' => 'nullable|string|max:255',
            'role' => 'required|string',
            'password' => 'required|string|confirmed',

        ];
    }
}
