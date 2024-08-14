<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use Illuminate\Http\Request;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url'
        ]);

        $path = $request->file('image')->store('public/banners');

        $banner = new Banner();
        $banner->title = $validatedData['title'];
        $banner->image = $path;
        $banner->link = $validatedData['link'];
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được tạo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        //
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        //
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/banners');
            $banner->image = $path;
        }

        $banner->title = $validatedData['title'];
        $banner->link = $validatedData['link'];
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        //
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được xóa.');
    }
}
