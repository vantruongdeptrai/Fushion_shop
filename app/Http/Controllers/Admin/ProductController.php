<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catelogue;
use App\Models\Color;
use App\Models\ProductGallery;
use App\Models\Size;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.products.';
    public function index()
    {
        $data = Product::query()->with(['catelogue', 'tag'])->latest('id')->get();
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $catalogues = Catelogue::query()->pluck('name', 'id')->all();
        $colors = Color::query()->pluck('name', 'id')->all();
        $sizes = Size::query()->pluck('name', 'id')->all();
        $tags = Tag::query()->pluck('name', 'id')->all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('catalogues', 'colors', 'sizes', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
        $dataProduct = $request->except(['product_variants', 'tags', 'galleries']);//lấy data trừ đi biến thể , tag , galleries

        $dataProduct['is_active'] ??= 0;// dùng toán tử null coloasing , nếu null thì gán = 0
        $dataProduct['is_hot_deal'] ??= 0;
        $dataProduct['is_good_deal'] ??= 0;
        $dataProduct['is_new'] ??= 0;
        $dataProduct['is_show_home'] ??= 0;
        $dataProduct['view'] ??= 0;
        $dataProduct['slug'] = Str::slug($dataProduct['name']) . '-' . $dataProduct['sku'];//Biến đổi sku và name thành chuỗi nối nhau bằng -

        //Lưu ảnh , nếu 0 tồn tại thì thêm ảnh mới
        if (!empty($dataProduct['img_thumbnail'])) {
            $path = $request->file('img_thumbnail')->store('products', 'public');
            // Lưu đường dẫn vào mảng data
            $dataProduct['img_thumbnail'] = $path;
        }
        // dd($dataProduct);
        $product = Product::query()->create($dataProduct);


        $id = $product->id;

        // Xử lí biến thể
        $dataProductVariantsTmp = $request->product_variants;
        $dataProductVariants = [];

        foreach ($dataProductVariantsTmp as $key => $item) {
            $tmp = explode('-', $key);

            $imageKey = "product_variants.{$key}.image";
            if ($request->hasFile($imageKey)) {
                $image = $request->file($imageKey)->store('product_variants', 'public');
            } else {
                $image = !empty($item['current_image']) ? $item['current_image'] : null;
            }

            $dataProductVariants[] = [
                'product_id' => $id,
                'size_id' => $tmp[0],
                'color_id' => $tmp[1],
                'quantity' => $item['quantity'],
                'image' => $image
            ];
        }

        // Lưu các biến thể
        ProductVariant::insert($dataProductVariants);
        //dd($dataProductVariants);

        $dataProductGalleriesTmp = $request->file('galleries') ?: [];

        $dataProductGalleries = [];
        foreach ($dataProductGalleriesTmp as $image) {
            if ($image && $image->isValid()) {
                $dataProductGalleries[] = [
                    'product_id' => $id, // Đảm bảo bạn đã có $id từ sản phẩm đã tạo
                    'image' => $image->store('galleries', 'public')
                ];
            }
        }

        // Sử dụng insert thay vì create để thêm nhiều bản ghi cùng lúc
        if (!empty($dataProductGalleries)) {
            ProductGallery::insert($dataProductGalleries);
        }

        $dataProductTagsTmp = $request->tags;
        $dataProductTags = [];
        foreach ($dataProductTagsTmp as $key => $value) {
            $dataProductTags[] = [
                'product_id' => $id,
                'tag_id' => $value
            ];
            //dd($dataProductTags);   
        }
        $product = Product::find($id);
        if ($product) {
            // Sử dụng attach để gán các tag cho sản phẩm
            $product->tag()->attach($dataProductTags);

            // Nếu bạn muốn đồng bộ các tag, bạn có thể dùng sync thay vì attach
            // $product->tags()->sync($dataProductTagsTmp);

            // Tiếp tục xử lý khác nếu cần
        }
        //dd($dataProductTags);
        return redirect()->back()->with('success', 'Thêm sản phẩm thành công !');
        //        
    } 

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {


        $product = Product::with(['variants.size', 'variants.color', 'tag', 'galleries', 'catelogue'])->findOrFail($product->id);
        //dd($product);
        $product_variants = $product->variants;

        return view(self::PATH_VIEW . __FUNCTION__, compact('product', 'product_variants'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Product $product)
    {
        //
        $catalogues = Catelogue::query()->pluck('name', 'id')->all();
        $colors = Color::query()->pluck('name', 'id')->all();
        $sizes = Size::query()->pluck('name', 'id')->all();
        $tags = Tag::query()->pluck('name', 'id')->all();
        $product = Product::with(['variants', 'tag', 'galleries', 'catelogue'])->findOrFail($product->id);
        
        $allTags = Tag::all(); // Lấy tất cả tags để người dùng chọn
        // dd($product->catelogue);
        return view(self::PATH_VIEW . __FUNCTION__, compact('product', 'tags', 'colors', 'sizes', 'catalogues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $dataProduct = $request->except(['product_variants', 'tags', 'galleries']);

        $dataProduct['is_active'] ??= 0;
        $dataProduct['is_hot_deal'] ??= 0;
        $dataProduct['is_good_deal'] ??= 0;
        $dataProduct['is_new'] ??= 0;
        $dataProduct['is_show_home'] ??= 0;
        $dataProduct['view'] ??= $product->view; // Giữ nguyên số lượt xem
        $dataProduct['slug'] = Str::slug($dataProduct['name']) . '-' . $dataProduct['sku'];

        if ($request->hasFile('img_thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($product->img_thumbnail) {
                Storage::disk('public')->delete($product->img_thumbnail);
            }
            $path = $request->file('img_thumbnail')->store('products', 'public');
            $dataProduct['img_thumbnail'] = $path;
        }

        $product->update($dataProduct);

        // Cập nhật biến thể
        $dataProductVariantsTmp = $request->product_variants;
        $dataProductVariants = [];

        foreach ($dataProductVariantsTmp as $key => $item) {
            $tmp = explode('-', $key);

            $imageKey = "product_variants.{$key}.image";
            if ($request->hasFile($imageKey)) {
                // Xóa ảnh cũ nếu có
                $oldVariant = ProductVariant::where('product_id', $id)
                    ->where('size_id', $tmp[0])
                    ->where('color_id', $tmp[1])
                    ->first();
                if ($oldVariant && $oldVariant->image) {
                    Storage::disk('public')->delete($oldVariant->image);
                }
                $image = $request->file($imageKey)->store('product_variants', 'public');
            } else {
                $image = !empty($item['current_image']) ? $item['current_image'] : null;
            }

            $dataProductVariants[] = [
                'product_id' => $id,
                'size_id' => $tmp[0],
                'color_id' => $tmp[1],
                'quantity' => $item['quantity'],
                'image' => $image
            ];
        }

        // Xóa biến thể cũ và thêm biến thể mới
        ProductVariant::where('product_id', $id)->delete();
        ProductVariant::insert($dataProductVariants);

        // Cập nhật gallery
        if ($request->hasFile('galleries')) {
            $dataProductGalleriesTmp = $request->file('galleries');
            $dataProductGalleries = [];

            foreach ($dataProductGalleriesTmp as $image) {
                if ($image && $image->isValid()) {
                    $dataProductGalleries[] = [
                        'product_id' => $id,
                        'image' => $image->store('galleries', 'public')
                    ];
                }
            }

            // Xóa gallery cũ
            $oldGalleries = ProductGallery::where('product_id', $id)->get();
            foreach ($oldGalleries as $oldGallery) {
                Storage::disk('public')->delete($oldGallery->image);
            }
            ProductGallery::where('product_id', $id)->delete();

            // Thêm gallery mới
            if (!empty($dataProductGalleries)) {
                ProductGallery::insert($dataProductGalleries);
            }
        }

        // Cập nhật tags
        $dataProductTags = $request->tags ?? [];
        $product->tag()->sync($dataProductTags);

        return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        DB::transaction(function () use ($product) {
            $product = Product::findOrFail($product->id);

            // Xóa các bản ghi liên quan trong bảng con
            $product->galleries()->delete();
            $product->variants()->delete();
            $product->tag()->detach();

            $product->delete();
        });
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
    public function detail($slug)
    {
        $product = Product::query()->with('variants')->where('slug', $slug)->first();
        $colors = Color::query()->pluck('name', 'id')->all();
        $sizes = Size::query()->pluck('name', 'id')->all();

        return view('user.product-detail', compact('product', 'colors', 'sizes'));
    }

}
