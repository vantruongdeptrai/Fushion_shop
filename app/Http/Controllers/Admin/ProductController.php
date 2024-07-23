<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catelogue;
use App\Models\ProductColor;
use App\Models\ProductGallery;
use App\Models\ProductSize;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $colors = ProductColor::query()->pluck('name', 'id')->all();
        $sizes = ProductSize::query()->pluck('name', 'id')->all();
        $tags = Tag::query()->pluck('name', 'id')->all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('catalogues', 'colors', 'sizes', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $dataProduct = $request->all();//setup request , trừ các thuộc tính biến thể

        $dataProduct['is_active'] ??= 0; //để mặc định là 0 , toán từ null-coalescing , nếu $dataProduct['is_active'] thì gán nó bằng 0 , nếu khác null thì giữ nguyên giá trị
        $dataProduct['is_hot_deal'] ??= 0;
        $dataProduct['is_good_deal'] ??= 0;
        $dataProduct['is_new'] ??= 0;
        $dataProduct['is_show_home'] ??= 0;

        $dataProduct['slug'] = Str::slug($dataProduct['name']) . '-' . $dataProduct['sku'];//tạo slug bằng tên sản phẩm - sku

        if ($dataProduct['img_thumbnail']) {
            $path = 'public/products/' . $request->file('img_thumbnail')->getClientOriginalName();
            $dataProduct['img_thumbnail'] = Storage::put($path,file_get_contents($request->file('img_thumbnail')));
        }

        $dataProductVariantsTmp = $request->product_variants;//setup request data variants
        $dataProductVariants = [];// set mảng rỗng
        //
        //dd($dataProductVariantsTmp);
        foreach ($dataProductVariantsTmp as $key => $item) {
            $tmp = explode('-', $key);// phân tách chuỗi thành các kí tự ngăn cách bởi dấu - 
            // dd($tmp);
            $dataProductVariants[] = [
                'product_size_id' => $tmp[0],//set key = product_size_id , value = $tmp[0]
                'product_color_id' => $tmp[1],
                'quatity' => $item['quatity'],
                'image' => $item['image'] ?? null,
            ];
        }

        $dataProductTags = $request->tags;
        $dataProductGalleries = $request->product_galleries ?: [];
        //dd($request->all());
        try {
            DB::beginTransaction();

            /** @var Product $product */
            $product = Product::query()->create($dataProduct);

            foreach ($dataProductVariants as $dataProductVariant) {
                $dataProductVariant['product_id'] = $product->id;

                if ($dataProductVariant['image']) {
                    $dataProductVariant['image'] = Storage::put('products', $dataProductVariant['image']);
                }

                ProductVariant::query()->create($dataProductVariant);
            }

            $product->tag()->attach($dataProductTags);

            foreach ($dataProductGalleries as $image) {
                ProductGallery::query()->create([
                    'product_id' => $product->id,
                    'image' => Storage::put('products', $image)
                ]);
            }

            DB::commit();

            return redirect()->route('admin.products.index');
        } catch (\Exception $exception) {
            DB::rollBack();

            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        // $catalogues = Catelogue::query()->pluck('name','id')->all();
        // $colors = ProductColor::query()->pluck('name','id')->all();
        // $sizes = ProductSize::query()->pluck('name','id')->all();
        // $tags = Tag::query()->pluck('name','id')->all();

        $product = Product::with(['variants', 'tag', 'galleries', 'catelogue'])->findOrFail($product->id);
        //dd($product->variants);
        return view(self::PATH_VIEW . __FUNCTION__, compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        $catalogues = Catelogue::query()->pluck('name', 'id')->all();
        $colors = ProductColor::query()->pluck('name', 'id')->all();
        $sizes = ProductSize::query()->pluck('name', 'id')->all();
        $tags = Tag::query()->pluck('name', 'id')->all();
        $product = Product::with(['variants', 'tag', 'galleries', 'catelogue'])->findOrFail($product->id);
        $allTags = Tag::all(); // Lấy tất cả tags để người dùng chọn
        // dd($product->catelogue);
        return view(self::PATH_VIEW . __FUNCTION__, compact('product', 'tags', 'colors', 'sizes', 'catalogues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
        $dataProduct = $request->except(['product_variants', 'tags', 'product_galleries']);
        $dataProduct['is_active'] ??= 0;
        $dataProduct['is_hot_deal'] ??= 0;
        $dataProduct['is_good_deal'] ??= 0;
        $dataProduct['is_new'] ??= 0;
        $dataProduct['is_show_home'] ??= 0;
        $dataProduct['slug'] = Str::slug($dataProduct['name']) . '-' . $dataProduct['sku'];

        if ($request->hasFile('img_thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($product->img_thumbnail) {
                Storage::delete($product->img_thumbnail);
            }
            $dataProduct['img_thumbnail'] = Storage::put('products', $request->file('img_thumbnail'));
        }

        $dataProductVariantsTmp = $request->product_variants;
        $dataProductVariants = [];
        foreach ($dataProductVariantsTmp as $key => $item) {
            $tmp = explode('-', $key);
            $dataProductVariants[] = [
                'product_size_id' => $tmp[0],
                'product_color_id' => $tmp[1],
                'quantity' => $item['quantity'],
                'image' => $item['image'] ?? null,
            ];
        }

        $dataProductTags = $request->tags;
        $dataProductGalleries = $request->product_galleries ?: [];

            DB::beginTransaction();

            $product->update($dataProduct);

            // Xóa các biến thể cũ
            $product->variants()->delete();
            foreach ($dataProductVariants as $dataProductVariant) {
                $dataProductVariant['product_id'] = $product->id;
                if (isset($dataProductVariant['image']) && $dataProductVariant['image']) {
                    $dataProductVariant['image'] = Storage::put('products', $dataProductVariant['image']);
                }
                ProductVariant::create($dataProductVariant);
            }

            // Cập nhật tags
            $product->tag()->sync($dataProductTags);

            // Xóa các gallery cũ
            foreach ($product->galleries as $gallery) {
                Storage::delete($gallery->image);
                $gallery->delete();
            }
            foreach ($dataProductGalleries as $image) {
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image' => Storage::put('products', $image)
                ]);
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
        
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
        $colors = ProductColor::query()->pluck('name', 'id')->all();
        $sizes = ProductSize::query()->pluck('name', 'id')->all();

        return view('user.product-detail', compact('product', 'colors', 'sizes'));
    }
}
