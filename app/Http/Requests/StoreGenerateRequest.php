<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGenerateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|unique:generates,name,',
            'schema' => 'required',
            'module_type' => 'required',



        ];
    }
    public function messages(): array
    {
        return [];
    }
}
