<?php

namespace App\Http\Requests;

use App\Models;
use App\Models\ProductCatalogue;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ChildrenProductCatalogueRule;

class DeleteProductCatalogueRequest extends FormRequest
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
        $id = $this->route('id');
        return [
        'name' => [
                (new ChildrenProductCatalogueRule($id))
            ]
        ];
    }
}