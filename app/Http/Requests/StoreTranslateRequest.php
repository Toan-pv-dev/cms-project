<?php

namespace App\Http\Requests;

// use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class StoreTranslateRequest extends FormRequest
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

        // dd($this->input('option'));
        return [
            'translate_name' => 'required|string',
            'translate_canonical' => [
                'required',
                function ($attribute, $value, $fail) {
                    $option = $this->input('option');
                    // dd($option);
                    $exist = DB::table('routers')
                        ->where('canonical', $value)
                        ->where('language_id', '<>', $option['LanguageId'])
                        ->where('id', '<>', $option['id'])
                        ->exists();

                    // dd($exist);
                    if ($exist) {
                        $fail('Đường dẫn đã tồn tại, Hãy chọn đường dẫn khác');
                    }
                },
            ]


        ];
    }
    public function messages(): array
    {
        return [
            'translate_name.required' => 'Bạn chưa nhập tên',
            'translate_canonical.required' => 'Bạn chưa nhập mô tả'
        ];
    }
}
