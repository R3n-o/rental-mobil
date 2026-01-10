<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        
        try {
            $catResponse = Http::timeout(5)->get('http://127.0.0.1:8000/api/categories');
            $categories = $catResponse->json()['data'] ?? [];
        } catch (\Exception $e) {
            $categories = [];
        }

       
        try {
            
            $url = 'http://127.0.0.1:8000/api/cars';
            if ($request->has('category_id')) {
                $url .= '?category_id=' . $request->category_id;
            }
            
            $response = Http::timeout(5)->get($url);
            $cars = $response->json()['data'] ?? [];

            
            if ($request->has('category_id')) {
                $cars = array_filter($cars, function($car) use ($request) {
                    return $car['category_id'] == $request->category_id;
                });
            }

        } catch (\Exception $e) {
            $cars = [];
        }

        return view('welcome', compact('cars', 'categories'));
    }
}