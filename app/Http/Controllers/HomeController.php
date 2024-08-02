<?php

namespace App\Http\Controllers;

use App\Models\Catelogue;
use App\Models\Color;
use App\Models\ProductGallery;
use App\Models\Size;
use App\Models\Product;

use App\Models\Tag;
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
        $list_product = Product::simplePaginate(9);
        $catelogues = Catelogue::query()->take(5)->get();
        $tags = Tag::query()->take(10)->get();
        return view('user.product-list',compact('list_product','catelogues','tags'));
    }
    public function detail($slug)
    {
        $product = Product::query()->with(['variants','galleries'])->where('slug', $slug)->first();
        $colors = Color::query()->pluck('name', 'id')->all();
        $sizes = Size::query()->pluck('name', 'id')->all();
        return view('user.product-detail', compact('product', 'colors', 'sizes'));
    }
}
