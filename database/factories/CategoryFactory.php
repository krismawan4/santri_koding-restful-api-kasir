<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->jobTitle;
        $nameArr = explode(' ', $name);

        $name = trim($nameArr[0]) . rand();
        return [
            'name' => $name,
            'slug' => $name,
        ];
    }
}
