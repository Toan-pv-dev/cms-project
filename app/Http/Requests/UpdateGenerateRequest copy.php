<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGenerateRequest extends FormRequest
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
            'name' => 'required|unique:generates,name,' . $this->id . '',
            'schema' => 'required',
            'module_type' => 'required|gt:0',
        ];
    }
    public function messages(): array
    {
        return [
            // 'email.required' => 'Bạn chưa nhập email',
            // 'email.email' => 'Bạn chưa nhập đúng định dạng',
            // 'password.required' => 'Bạn chưa nhập mật khẩu'
        ];
    }
}
