<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest

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
        $serviceId = $this->route('service') ? $this->route('service')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services', 'name')->ignore($serviceId), // Ignore the current service for unique validation
            ],
            'description' => 'required|string',
            'eligibility' => 'required|string',
            'requirements' => 'required|string',
            'numberOfRequirements' => 'required|integer|min:1',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('services', 'slug')->ignore($serviceId), // Same here for slug
            ],
        ];
    }
}
