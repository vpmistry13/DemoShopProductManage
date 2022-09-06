<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the featured products in Home Page.
     */
    public function getFeaturedProducts(Request $request){
        $uri = $request->path();
        $results = Product::with('getCategory')->orderBy('id')->paginate(10);

        $products = '';
        if ($request->ajax()) {
            foreach ($results as $product) {
                //Single View pattern
                $products.= view('products.single-product',compact('product'));
            }
            return $products;
        }
        
        return view('welcome',compact(['uri','products']));
    }
}
