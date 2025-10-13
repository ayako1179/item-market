<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'brand_name' => $this->faker->company(),
            'price' => $this->faker->numberBetween(1000,10000),
            'description' => $this->faker->sentence(),
            'image_path' => 'storage/products/sample.jpeg',
            'condition_id' => Condition::factory(),
            'is_sold' => false,
        ];
    }
}
