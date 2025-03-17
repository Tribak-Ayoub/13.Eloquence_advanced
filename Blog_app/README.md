# Laravel Testing Tutorial for Blog

## Introduction

Testing is crucial to ensure that your Laravel blog functions correctly. In this tutorial, we will focus on testing the article creation functionality using Laravel's built-in testing framework, PHPUnit. We will create tests to verify:

- That authenticated users can create articles.
- That guests are prevented from creating articles.
- That validation rules are enforced.

## Setting Up Tests

Before writing tests, we need to ensure our environment is correctly set up. Laravel provides a separate testing database to prevent tests from affecting real data.

### Creating the `.env.testing` File

By default, Laravel uses the `.env` file for configuration, but it is recommended to create a `.env.testing` file for testing purposes. If you don't have one, create it with the following command:

```bash
cp .env .env.testing
```

Then, update your `.env.testing` file to use a dedicated test database:

```ini
DB_CONNECTION=mysql
DB_DATABASE=your_test_db
DB_USERNAME=root
DB_PASSWORD=
```

After updating the file, clear the configuration cache to apply the changes:

```bash
php artisan config:clear
```

### Running Migrations for the Test Database

To ensure that your test database has the necessary tables, run:

```bash
php artisan migrate:fresh --seed --env=testing
```

This step ensures that tests do not interfere with your production or development database.

## Creating a Feature Test for Articles

Laravel provides a command to generate test files. Run the following command to create a test for article functionality:

```bash
php artisan make:test ArticleTest
```

This will generate a new file in the `tests/Feature` directory named `ArticleTest.php`.

## Writing the Test

Open `tests/Feature/ArticleTest.php` and update it with the following code:

```php
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
```

### Explanation of Test Cases

1. **`test_authenticated_user_can_create_article`**

   - Creates a user.
   - Authenticates the user and submits an article form.
   - Checks if the article is stored in the database.
   - Ensures a successful response (HTTP status 302 for redirect).

2. **`test_guest_cannot_create_article`**

   - Attempts to create an article without authentication.
   - Ensures the user is redirected to the login page.
   - Confirms the article was not stored in the database.

3. **`test_article_creation_requires_validation`**
   - Tries to submit an empty form while authenticated.
   - Ensures validation errors are triggered.
   - Confirms errors for missing `title` and `content` fields.

## Running the Tests

To execute your tests, run the following command:

```bash
php artisan test --filter=ArticleTest
```

This will run only the tests inside `ArticleTest.php`.

## Conclusion

This tutorial covered:

- How to set up and configure Laravel for testing.
- Creating the `.env.testing` file and configuring a separate database for testing.
- Writing feature tests for article creation.
- Ensuring authentication, validation, and database assertions are working correctly.
