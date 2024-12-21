<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserCatalogueRequest extends FormRequest
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
            // 'email' => 'required|email|unique:user,email,' . $this->id . '|max:191',
            'name' => 'required|string|unique:userCatalogue,name,' . $this->id  . '|max:191',
            'description' => 'required|string|min:3',
            // 'password' => 'required|string|min:6',
            // 'reenter_password' => 'required|string|same:password'

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
