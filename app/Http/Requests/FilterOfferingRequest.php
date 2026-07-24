<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FilterOfferingRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string'],
            'origin' => ['nullable', 'string'],
            'process' => ['nullable', 'string'],
            'score' => ['nullable', 'integer', 'in:80,85,90'],
            'main_tastes' => ['nullable', 'array'],
            'main_tastes.*' => ['integer', 'distinct', 'exists:olfactory_taxonomies,id'],
            'specific_tastes' => ['nullable', 'array'],
            'specific_tastes.*' => ['integer', 'distinct', 'exists:olfactory_taxonomies,id'],
        ];
    }
}
