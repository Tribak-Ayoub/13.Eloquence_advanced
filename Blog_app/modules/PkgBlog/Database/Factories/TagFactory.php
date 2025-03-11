<?php

namespace Modules\PkgBlog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PkgBlog\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\PkgBlog\Models\Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;
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
