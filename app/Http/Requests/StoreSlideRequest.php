<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlideRequest extends FormRequest
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
        $slideId = $this->route('id');
        return [
            'name' => 'required|',
            'keyword' => 'required|string|max:255|unique:slides,keyword,' . $slideId,
            'slide.image' => 'required',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên slide.',
            'keyword.required' => 'Bạn chưa nhập từ khóa.',
            'keyword.unique' => 'Từ khóa đã tồn tại, vui lòng chọn từ khóa khác.',
            'slide.image.required' => 'Bạn chưa chọn hình ảnh cho slide.',
        ];
    }
}
