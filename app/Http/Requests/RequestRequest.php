<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string',
            'email' => 'nullable|email',
            'status' => 'nullable|string',
            'files_path' => 'nullable|array',
            'files_path.*' => 'file|mimes:jpg,png,pdf,docx|max:10240',
        ];
    }
}
