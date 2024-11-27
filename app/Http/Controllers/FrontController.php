<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
// In your controller, e.g., HomeController.php


    public function index(Request $request)
    {
        $slides = [
            [
                'image' => 'images/slide-01.jpg',
                'title' => 'Women Collection 2018',
                'subtitle' => 'NEW SEASON',
                'link' => 'product.html',
                'button_text' => 'Shop Now',
                'title_animation' => 'fadeInDown',
                'subtitle_animation' => 'fadeInUp',
                'button_animation' => 'zoomIn',
            ],
            [
                'image' => 'images/slide-02.jpg',
                'title' => 'Men New-Season',
                'subtitle' => 'Jackets & Coats',
                'link' => 'product.html',
                'button_text' => 'Shop Now',
                'title_animation' => 'rollIn',
                'subtitle_animation' => 'lightSpeedIn',
                'button_animation' => 'slideInUp',
            ],
            [
                'image' => 'images/slide-03.jpg',
                'title' => 'Men Collection 2018',
                'subtitle' => 'New arrivals',
                'link' => 'product.html',
                'button_text' => 'Shop Now',
                'title_animation' => 'rotateInDownLeft',
                'subtitle_animation' => 'rotateInUpRight',
                'button_animation' => 'rotateIn',
            ],
        ];
    
        return view('landing.home', compact('slides'));  
    }
    public function shopproducts(Request $request)
    {
        return view('landing.products');
    }
    public function shopingcarts(Request $request)
    {
        return view('landing.cart.shoping-cart');
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