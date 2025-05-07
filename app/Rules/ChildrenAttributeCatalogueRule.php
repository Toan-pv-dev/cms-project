<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\AttributeCatalogue;

class ChildrenAttributeCatalogueRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $flag = AttributeCatalogue::isChildrenNode($this->id);
        if (!$flag) {
            $fail('Không thể xóa do vẫn còn danh mục con');
        }
    }
}
