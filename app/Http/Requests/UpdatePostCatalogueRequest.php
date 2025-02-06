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
        // echo 1;
        // die();
        return [
            'name' => 'required|string',
            'canonical' => 'required|unique:routers,canonical,' . $this->id . ' module_id',



            // 'parent_id' => 'gt:0',


        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên',
            'canonical.required' => 'Bạn chưa nhập đường dẫn ',
            'canonical.unique' => 'Đường dẫn đã tồn tại ',
            // 'parent_id.gt' => 'Chưa có mục cha'

        ];
    }
}
