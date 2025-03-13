<?php

namespace Modules\PkgBlog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PkgBlog\App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\PkgBlog\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->word,
        ];
    }
}
