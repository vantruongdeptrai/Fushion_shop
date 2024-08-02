<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Color;
use App\Models\ProductGallery;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Size;
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
        Size::query()->truncate();
        Color::query()->truncate();
        Tag::query()->truncate();

        Tag::factory(10)->create();

        // S, M, L, XL, XXL
        foreach (['M', 'L', 'XL'] as $item) {
            Size::query()->create([
                'name' => $item
            ]);
        }

        //
        foreach (['#0000FF', '#FF00FF', '#33CCFF'] as $item) {
            Color::query()->create([
                'name' => $item
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            $name = fake()->text(10);
            $description = fake()->text(50);
            $content = fake()->text(30);
            $user_manual = fake()->text(50);
            Product::query()->create([
                'catelogue_id' => rand(1,5),
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(8),
                'sku' => Str::random(8) . $i,
                'img_thumbnail' => 'https://canifa.com/img/1000/1500/resize/6/o/6ot24s002-sb001-1.webp',
                'price_regular' => 600000,
                'price_sale' => 499000,
                'description' => $description,
                'content' => $content,
                'material' => Str::random(5),
                'user_manual' => $user_manual,
                'views' => rand(5,100),
                'is_active'=> 1,
                'is_hot_deal'=> 1,
                'is_good_deal'=> 1,
                'is_new'=> 1,
                'is_show_home'=> 1,
                'created_at'=> now(),
                'updated_at'=> now()
            ]);
        }

        for ($i = 1; $i < 11; $i++) {
            ProductGallery::query()->insert([
                [
                    'product_id' => $i,
                    'image' => 'https://canifa.com/img/1000/1500/resize/6/o/6ot24s002-sb001-1.webp',
                ],
                [
                    'product_id' => $i,
                    'image' => 'https://canifa.com/img/1000/1500/resize/6/o/6ot24s002-sb001-l-1-u.webp',
                ],
            ]);
        }
        
        for ($i = 1; $i < 11; $i++) {
            DB::table('product_tag')->insert([
                [
                    'product_id' => $i,
                    'tag_id' => rand(1, 5)
                ],
                [
                    'product_id' => $i,
                    'tag_id' => rand(6, 10)
                ]
            ]);
        }

        for ($productID = 1; $productID < 11; $productID++) {
            $data = [];
            for ($sizeID = 1; $sizeID < 4; $sizeID++) {
                for ($colorID = 1; $colorID < 4; $colorID++) {
                    $data[] = [
                        'product_id' => $productID,
                        'size_id' => $sizeID,
                        'color_id' => $colorID,
                        'quantity' => 100,
                        'image' => 'https://canifa.com/img/1000/1500/resize/6/o/6ot24s002-sb001-l-1-u.webp',
                        'created_at'=> now(),
                        'updated_at'=> now()
                    ];
                }
            }

            DB::table('product_variants')->insert($data);
        }
    }
}
