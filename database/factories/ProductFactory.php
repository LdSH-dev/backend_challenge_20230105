<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->numerify('########'), // Gera um código único
            'status' => $this->faker->randomElement(['published', 'trash', 'draft']),
            'imported_t' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_t' => Carbon::now()->format('Y-m-d H:i:s'),
            'url' => $this->faker->url,
            'creator' => $this->faker->name,
            'created_t' => Carbon::now()->subYears(rand(1, 5))->format('Y-m-d H:i:s'), // Gera uma data formatada
            'last_modified_t' => Carbon::now()->subYears(rand(0, 5))->format('Y-m-d H:i:s'), // Gera uma data formatada
            'product_name' => $this->faker->words(3, true),
            'quantity' => $this->faker->randomNumber(2) . ' units',
            'brands' => $this->faker->company,
            'categories' => $this->faker->words(2, true),
            'labels' => $this->faker->words(3, true),
            'cities' => $this->faker->city,
            'purchase_places' => $this->faker->city,
            'stores' => $this->faker->company,
            'ingredients_text' => $this->faker->sentence,
            'traces' => $this->faker->word,
            'serving_size' => $this->faker->randomNumber(2) . 'g',
            'nutriscore_score' => $this->faker->numberBetween(-15, 40),
            'nutriscore_grade' => $this->faker->randomElement(['a', 'b', 'c', 'd', 'e']),
            'main_category' => $this->faker->word,
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
