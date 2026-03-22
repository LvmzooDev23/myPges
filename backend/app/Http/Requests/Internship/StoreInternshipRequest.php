<?php

namespace App\Http\Requests\Internship;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInternshipRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['remote', 'onsite', 'hybrid'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'slots' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'closed'])],
            'requirements' => ['nullable', 'string'],
        ];
    }
}
