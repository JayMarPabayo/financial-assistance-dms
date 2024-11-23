<?php

namespace App\Http\Requests;

use App\Models\Request;
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
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'name_extension' => 'nullable|string|in:' . implode(',', Request::$nameExtensions),
            'deceased_person' => 'nullable|string',
            'gender' => 'required|string',
            'birthdate' => 'required|date|before:-18 years',
            'address' => 'required|string',
            'contact' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:50',
            'attachments' => 'sometimes|array',
            'attachments.*.file_path' => 'required|file|mimes:pdf,jpeg,png,jpg,gif,doc,docx|max:2048',
        ];
    }


    public function messages(): array
    {
        return [
            'firstname.required' => 'The first name is required.',
            'firstname.string' => 'The first name must be a valid string.',
            'firstname.max' => 'The first name must not exceed 255 characters.',
            'middlename.string' => 'The middle name must be a valid string.',
            'middlename.max' => 'The middle name must not exceed 255 characters.',
            'lastname.required' => 'The last name is required.',
            'lastname.string' => 'The last name must be a valid string.',
            'lastname.max' => 'The last name must not exceed 255 characters.',
            'address.required' => 'The address is required.',
            'address.string' => 'The address must be a valid string.',
            'contact.required' => 'The contact number is required.',
            'contact.string' => 'The contact number must be a valid string.',
            'contact.max' => 'The contact number must not exceed 15 characters.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email must not exceed 255 characters.',
            'status.string' => 'The status must be a valid string.',
            'status.max' => 'The status must not exceed 50 characters.',
            'birthdate.required' => 'The birthdate is required.',
            'birthdate.date' => 'The birthdate must be a valid date.',
            'birthdate.before' => 'You must be at least 18 years old.',
            'gender.required' => 'The gender is required.',
            'gender.string' => 'The gender must be a valid string.',
            'gender.in' => 'The gender must be one of the following: Male, Female, Other.',
            'attachments.array' => 'The attachments must be an array.',
            'attachments.*.file_path.required' => 'Each attachment must have a file.',
            'attachments.*.file_path.file' => 'Each attachment must be a valid file.',
            'attachments.*.file_path.mimes' => 'Each attachment must be of type: pdf, jpeg, png, jpg, gif, doc, docx.',
            'attachments.*.file_path.max' => 'Each attachment must not exceed 2MB in size.',
        ];
    }
}
