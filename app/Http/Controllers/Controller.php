<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Controller
{
    protected $language;
    public function __construct()
    {
        $this->language = session('applocale');
    }
    public function currentLanguage()
    {
        return 1;
    }
}
