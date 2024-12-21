<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostCatalogueRequest extends FormRequest
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
            'name' => 'required|string|unique:postCatalogue,name',
            'description' => 'required|string',


        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên',
            'name.unique' => 'Tên nhóm đã tồn tại ',

            'description.required' => 'Bạn chưa nhập mô tả'
        ];
    }
}
