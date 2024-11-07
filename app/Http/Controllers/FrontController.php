<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{

    public function index(Request $request)
    {
        return view('landing.home');
    }
    public function shopproducts(Request $request)
    {
        return view('landing.products');
    }
    public function shopingcarts(Request $request)
    {
        return view('landing.shoping-cart');
    }
    public function blogs(Request $request)
    {
        return view('landing.blogs');
    }
  
    public function contactUs(Request $request)
    {
        return view('landing.contact-us');
    }
    
    public function aboutUs(Request $request)
    {
        return view('landing.about-us');
    }
}