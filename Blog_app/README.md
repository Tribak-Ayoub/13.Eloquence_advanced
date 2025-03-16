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
php artisan migrate --env=testing
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

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;

class ArticleTest extends TestCase
{
    use RefreshDatabase; // Ensures database is reset before each test

    public function test_authenticated_user_can_create_article()
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Act as the authenticated user and send a POST request
        $response = $this->actingAs($user)->post(route('articles.store'), [
            'title' => 'Test Article',
            'content' => 'This is a test article.',
        ]);

        // Assert the request was successful (redirects after storing data)
        $response->assertStatus(302);

        // Ensure the article was saved in the database
        $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
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

    public function test_article_creation_requires_validation()
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Act as the user and send an empty request
        $response = $this->actingAs($user)->post(route('articles.store'), []);

        // Assert that validation errors are present for required fields
        $response->assertSessionHasErrors(['title', 'content']);
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
