<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('website.pages.about');
    }

    public function services()
    {
        return view('website.pages.services');
    }

    public function giving()
    {
        return view('website.pages.giving');
    }

    public function privacy()
    {
        return view('website.pages.privacy');
    }

    public function terms()
    {
        return view('website.pages.terms');
    }

    public function cookies()
    {
        return view('website.pages.cookies');
    }
    
    public function sell()
    {
        return view('website.pages.sell');
    }
}