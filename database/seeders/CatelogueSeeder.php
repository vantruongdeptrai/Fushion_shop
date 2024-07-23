<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        // $now = Carbon::now(); // Lấy thời gian hiện tại
        // for($i=1;$i<8;$i++){
        //     Catelogue::create([
        //         'name' => fake(8),
        //         'cover' => 'catalogues/vKKxcaHHiK5qQCXPgBmVQ8awfgz7Sglh16u8QiZm.jpg',
        //         'is_active'   => 1,
        //         'created_at'  => $now,
        //         'updated_at'  => $now,
        //     ]);
        // }
        Catelogue::factory()->count(7)->create();
    }
}
