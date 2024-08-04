<?php

namespace App\Http\Controllers;

use App\Models\Catelogue;
use App\Models\Color;
use App\Models\Order;
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
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::query()->with('variants')->get();
        $catelogues = Catelogue::query()->orderBy('id','desc')->limit(4)->get();  
        //dd($catelogues);
        return view('user.home',compact('products','catelogues'));
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
    public function myAccount(){
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->with('orderItems')->orderBy('created_at', 'desc')->paginate(10);
        return view('user.my-account', compact('orders'));
    }
}
