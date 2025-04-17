<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Change the application language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLanguage($locale)
    {
        // Walidacja dostępnych języków
        if (!in_array($locale, ['en', 'pl'])) {
            $locale = 'pl'; // Domyślnie polski
        }
        
        Session::put('locale', $locale);
        App::setLocale($locale);
        
        return redirect()->back();
    }
}
