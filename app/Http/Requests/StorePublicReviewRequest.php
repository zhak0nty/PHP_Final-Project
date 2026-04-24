<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePublicReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kind' => ['nullable', Rule::in(['review', 'complaint'])],
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'text' => ['required_without:complaint_text', 'nullable', 'string', 'max:2000'],
            'complaint_name' => ['required_if:kind,complaint', 'string', 'max:255'],
            'complaint_phone' => ['nullable', 'string', 'max:255'],
            'complaint_text' => ['required_without:text', 'nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required_without' => 'Please provide review text.',
            'complaint_name.required_if' => 'Please provide your name for a complaint.',
            'complaint_text.required_without' => 'Please provide complaint details.',
        ];
    }
}
