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

            'attachments' => 'sometimes|array',
            'attachments.*.file_path' => 'required|file|mimes:pdf,jpeg,png,jpg,gif,doc,docx',
        ];
    }


    public function messages(): array
    {
        return [
            'attachments.*.file_path.required' => 'Attachment required.',
            'attachments.*.file_path.file' => 'The :attribute must be a file.',
            'attachments.*.file_path.mimes' => 'The :attribute must be a file of type: :values.',
        ];
    }
}
