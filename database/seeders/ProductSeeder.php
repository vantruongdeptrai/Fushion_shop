<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductGallery;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\ProductSize;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Schema::disableForeignKeyConstraints();

        ProductVariant::query()->truncate();
        ProductGallery::query()->truncate();
        DB::table('product_tag')->truncate();
        Product::query()->truncate();
        ProductSize::query()->truncate();
        ProductColor::query()->truncate();
        Tag::query()->truncate();
        
        Tag::factory(15)->create();
        
        //S, M , L , XL

        foreach(['S','M','L','XL','XXL'] as $item){
            ProductSize::query()->create([
                'name'=> $item
            ]);
        }
        foreach(['blue','red','yellow','black','green'] as $item){
            ProductColor::query()->create([
                'name'=> $item 
            ]);
        }
        for($i=1;$i<101;$i++){
            $name = fake()->text(100);
            Product::query()->create([
                'catelogue_id'=>rand(2,7),
                'name'=>$name,
                'slug'=>Str::slug($name).'-'.Str::random(8),
                'sku'=>Str::random(8).$i,
                'img_thumbnail'=>'https://media.canifa.com/Simiconnector/Nam_banner-cate_desktop-19.04a.webp',
                'price_regular'=>600000,
                'price_sale'=>400000,
            ]);
        }
        for($i=1;$i<101;$i++){
            ProductGallery::query()->insert(
                [
                    [
                        'product_id'=>$i,
                        'image'=>'https://canifa.com/img/486/733/resize/8/b/8bs24s002-sk010-1-thumb.webp'
                    ],
                    [
                        'product_id'=>$i,
                        'image'=>'https://media.canifa.com/Simiconnector/Nam_Quan_sooc_desktop-23.04.webp'
                    ],
                ]
            );
        }
        for ($i = 1; $i <101; $i++) {
            DB::table('product_tag')->insert([
                ['product_id' => $i, 'tag_id' => rand(1, 8)],
                ['product_id' => $i, 'tag_id' => rand(9, 15)],
            ]);
        }        

        for($productID=1;$productID<101;$productID++){
            $data = [];
            for($sizeID=1;$sizeID<6;$sizeID++){
                for($colorID=1;$colorID<6;$colorID++){
                    $data[] = [
                        'product_id'=>$productID,
                        'product_size_id'=>$sizeID,
                        'product_color_id'=>$colorID,
                        'quantity'=>100,
                        'image'=>'https://media.canifa.com/Simiconnector/Nam_Quan_sooc_desktop-23.04.webp'
                    ];
                }
            }
        }
        DB::table('product_variants')->insert($data);
    }
}
