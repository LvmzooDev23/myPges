<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProfileRequest extends FormRequest
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
            'first_name' => ['nullable', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'student_number' => ['nullable', 'string', 'max:64'],
            'phone' => ['nullable', 'string', 'max:64'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'university' => ['nullable', 'string', 'max:255'],
            'degree' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string', 'max:5000'],
            'linkedin_url' => ['nullable', 'string', 'max:500', 'url'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('linkedin_url') && $this->linkedin_url === '') {
            $this->merge(['linkedin_url' => null]);
        }
    }
}
