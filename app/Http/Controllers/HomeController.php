<?php

namespace App\Http\Controllers;

use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Product;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::query()->with('variants')->get();
        //$slug = Product::query()->get();
        //dd($slug);
        return view('user.home',compact('products'));
    }
    public function listProduct(){
        $list_product = Product::paginate(10);
        return view('user.product-list',compact('list_product'));
    }
    public function detail($slug)
    {
        $product = Product::query()->with('variants')->where('slug', $slug)->first();
        $colors = ProductColor::query()->pluck('name', 'id')->all();
        $sizes = ProductSize::query()->pluck('name', 'id')->all();

        return view('user.product-detail', compact('product', 'colors', 'sizes'));
    }
}
