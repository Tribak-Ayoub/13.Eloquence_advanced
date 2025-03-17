<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\PkgBlog\App\Models\Category;
use Modules\PkgBlog\App\Models\Tag;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations and only seed the roles and permissions
        $this->artisan('migrate:fresh'); // Reset the database
        $this->artisan('db:seed', ['--class' => 'PermissionsSeeder']); // Seed permissions only
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']); // Seed roles only
    }

    public function test_authenticated_user_can_create_article()
    {
        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testUser@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Assign a role to the user
        $user->assignRole('editor');

        $category = Category::factory()->create(); 
        $tags = Tag::factory()->count(3)->create();

        // Send a POST request with authentication
        $response = $this->actingAs($user)->post(route('articles.store'), [
            'title' => 'test article',
            'content' => 'this is a test article',
            'category' => $category->id,
            'tags' => $tags->pluck('id')->toArray(),
        ]);

        // Assert that the user is redirected to the articles page
        $response->assertStatus(302);  // 302 is typically for redirects (e.g., to the article list page)

        // Ensure the article was saved in the database
        $this->assertDatabaseHas('articles', [
            'title' => 'test article',
            'category_id' => $category->id,
        ]);
    }

    public function test_guest_cannot_create_article()
    {
        // Send a POST request without authentication
        $response = $this->post(route('articles.store'), [
            'title' => 'Guest Article',
            'content' => 'Should not be created.',
        ]);

        // Assert that the user is redirected to the login page
        $response->assertRedirect(route('login'));

        // Ensure the article was not saved in the database
        $this->assertDatabaseMissing('articles', ['title' => 'Guest Article']);
    }

    public function test_authenticated_user_cannot_create_article_with_invalid_data()
    {
        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testUser@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Assign a role to the user
        $user->assignRole('editor');

        // Send a POST request with missing fields (invalid data)
        $response = $this->actingAs($user)->post(route('articles.store'), [
            'title' => '', // Invalid title (empty)
            'content' => '', // Invalid content (empty)
            'category' => null, // Invalid category (null)
            'tags' => [], // Invalid tags (empty array)
        ]);

        // Assert that validation errors are present for the required fields
        $response->assertSessionHasErrors(['title', 'content', 'category', 'tags']);
    }
}
