<?php

namespace Modules\PkgBlog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PkgBlog\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\PkgBlog\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->word,
        ];
    }
}
