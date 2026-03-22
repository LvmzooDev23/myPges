<?php

namespace App\Http\Requests\Internship;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInternshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(['remote', 'onsite', 'hybrid'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'slots' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'closed'])],
            'requirements' => ['nullable', 'string'],
        ];
    }
}
