<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {

        return [
            'name' => 'required',
            'canonical' => [
                'required',
                Rule::unique('post_language', 'canonical')
                    ->ignore($this->post_id, 'post_id')
                    ->where(function ($query) {
                        return $query->where('canonical', '!=', $this->canonical);
                    }),
            ],
            'post_catalogue_id' => "gt:0",

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập vào ô tiêu đề',
            'canonical.required' => 'Bạn chưa nhập vào ô tiêu đề',
            'canonical.sometimes' => 'Đường dẫn đã tồn tại, Hãy chọn đường dẫn khác',
            'parent_id.required' => 'Bạn chưa nhập thư mục cha',
        ];
    }
}
