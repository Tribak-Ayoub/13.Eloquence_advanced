<?php

namespace Modules\PkgBlog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PkgBlog\App\Models\Article;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\PkgBlog\App\Models\Article>
 */
class ArticleFactory extends Factory
{

    protected $model = Article::class;

    public function definition(): array
    {
        return [
            //
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraph(5),
            'category_id' => $this->faker->numberBetween(1, 10),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
