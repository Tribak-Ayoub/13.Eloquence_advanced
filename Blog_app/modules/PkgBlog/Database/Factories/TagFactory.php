<?php

namespace Modules\PkgBlog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PkgBlog\App\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\PkgBlog\App\Models\Tag>
 */
class TagFactory extends Factory
{

    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->word,
        ];
    }
}
