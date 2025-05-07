<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Routing\Controller;
use App\Repositories\AttributeRepository;
use App\Models\Language;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    protected $attributeRepository;
    protected $language;

    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;

        $this->middleware(function (Request $request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    // Uncomment and use if needed
    public function getAttribute(Request $request)
    {


        $payload = $request->input();
        $attributes = $this->attributeRepository->searchAttributes($payload['search'], $payload['option'], $this->language);
        $attributeMapped = $attributes->map(function ($attribute) {
            return [
                'id' => $attribute->id,
                'text' => $attribute->attribute_language->first()->name,
            ];
        })->all();
        return response()->json(['item' => $attributeMapped]);
    }
}
