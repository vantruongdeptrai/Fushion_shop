<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Catelogue>
 */
class CatelogueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = Carbon::now();
        return [
            'name' => $this->faker->word,
            'cover' => $this->faker->image('storage/app/public/catalogues', 640, 480, null, false), // Tạo ảnh ngẫu nhiên
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
