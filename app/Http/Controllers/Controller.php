<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Language;
use Illuminate\Http\Request;

class Controller
{
    protected $language;
    public function currentLanguage()
    {
        $locale = Session::get('app_locale', config('app.locale'));
        $language = Language::where('canonical', $locale)->first();
        return $language->id;
    }
}
