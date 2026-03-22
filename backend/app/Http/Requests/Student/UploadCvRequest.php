<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UploadCvRequest extends FormRequest
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
            'cv' => ['required_without:file', 'file', 'mimes:pdf,docx', 'max:10240'],
            'file' => ['required_without:cv', 'file', 'mimes:pdf,docx', 'max:10240'],
        ];
    }
}
