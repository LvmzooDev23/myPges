<?php

namespace App\Http\Requests\Evaluation;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
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
            'application_id' => ['required', 'exists:applications,id'],
            'score' => ['required', 'integer', 'min:1', 'max:5'],
            'criteria' => ['nullable', 'array'],
            'comments' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
