<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Catelogue;

class CatelogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 6; $i++) {
            DB::table('catelogues')->insert([
                'name' => Str::random(5),
                'cover' => 'https://picsum.photos/seed/' . Str::random(5) . '/200/300'
            ]);
        }
        //Catelogue::factory()->count(10)->create();
    }
}
