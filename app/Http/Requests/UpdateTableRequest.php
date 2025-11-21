<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTableRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'table_number' => 'nullable|string|unique:tables,table_number,' . $this->table,
            'capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:available,occupied,reserved',
        ];
    }
}
