<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'q' => ['sometimes', 'string'],
            'from' => ['sometimes', 'date_format:Y-m-d H:i:s'],
            'to' => ['sometimes', 'date_format:Y-m-d H:i:s'],
            'categories' => ['sometimes', 'string'],
            'sources' => ['sometimes', 'string'],
            'authors' => ['sometimes', 'string'],
            'per_page' => ['sometimes', 'integer'],
        ];
    }
}
