# Laravel Testing Tutorial for Blog

## Introduction
### **Laravel Modular System Setup Instructions**

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

}
```

### **5. Register Service Providers**

Now, you need to register the module service providers in your `AppServiceProvider.php` file so that Laravel loads the resources for each module.

In `app/Providers/AppServiceProvider.php`, modify the `register` method to include the module providers:

```php
public function register(): void
{
    // Register the service providers for each module
    $this->app->register(Modules\Core\App\Providers\CoreServiceProvider::class);
    $this->app->register(Modules\PkgBlog\App\Providers\PkgBlogServiceProvider::class);
}
```

### **6. Define Routes for Each Module**

Inside the `Routes` folder of your module, define the routes for that module. For example, for `PkgBlog`, create the following route definition in a file like `web.php`:


# Form Request Validation in Laravel

This project uses Laravel Form Request classes to handle validation and authorization for incoming requests. Form Requests help keep controllers clean by encapsulating validation logic.

## What is a Form Request?
A Form Request is a custom request class that contains validation rules and authorization logic for a specific action, such as creating or updating an article.

## Creating a Form Request
To generate a Form Request class, use the Artisan command:
```bash
php artisan make:request ArticleRequest
```
This creates a file in `app/Http/Requests/ArticleRequest.php`.

## Structure of a Form Request
A Form Request contains two main methods:

### 1. **authorize()**
This method determines if the user is authorized to make the request.
```php
public function authorize(): bool
{
    return true; // Set to false if the user shouldn't be allowed
}
```

### 2. **rules()**
This method defines the validation rules for the request.
```php
public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'array',
        'tags.*' => 'exists:tags,id'
    ];
}
```

## Applying a Form Request in a Controller
Instead of manually writing validation logic in controllers, pass the Form Request as a parameter:
```php
public function store(ArticleRequest $request)
{
    $article = Article::create($request->validated());
    return redirect()->route('articles.index')->with('success', 'Article created successfully.');
}
```

## Customizing Error Messages
Custom error messages can be defined using the `messages()` method:
```php
public function messages(): array
{
    return [
        'title.required' => 'The title field is required.',
        'content.required' => 'Content cannot be empty.',
    ];
}
```

## Authorization Logic in Form Requests
You can use the `authorize()` method to restrict access based on user roles:
```php
public function authorize(): bool
{
    return auth()->user()->can('create', Article::class);
}
```

## Benefits of Using Form Requests
- Keeps controllers clean and organized.
- Centralizes validation logic.
- Allows for custom error messages.
- Supports authorization logic.

## Conclusion
Using Laravel Form Requests simplifies validation and ensures that all incoming data is properly validated before being processed in controllers. It enhances code maintainability and security.

### **1. Install the Laravel Lang Package**

Run this command to install the package:

```bash
composer require laravel-lang/lang
```

---

### **2. Install Languages (e.g., English and French)**

After the package is installed, use the `lang:install` command to download and install the translation files for the languages you want to support.

For example, to install English (`en`) and French (`fr`):

```bash
php artisan lang:install en fr
```

## This will download and install the translation files for English and French in the `resources/lang` directory.

### **3. Custom Translations**

You can add custom translations in your language files located in the `resources/lang/{lang}` directory. For example:

**English Translations (resources/lang/en/messages.php):**

```php
return [
    'welcome' => 'Welcome to our blog!',
    // Other custom translations...
];
```

**French Translations (resources/lang/fr/messages.php):**

```php
return [
    'welcome' => 'Bienvenue sur notre blog!',
    // Other custom translations...
];
```

---

### **4. Use Translations in Views**

You can now use Laravel’s built-in `__()` helper function to display translations in your views. For example:

```blade
<!-- auth.failed will show the translation for failed login attempts -->
{{ __('auth.failed') }}

<!-- Custom translation message in messages.php -->
{{ __('messages.welcome') }}
```

The `__('messages.welcome')` translation will look for the `welcome` key in your language files (e.g., `resources/lang/en/messages.php`).

# Article Policies in Laravel

This project uses Laravel Policies to handle authorization for managing articles. Policies ensure that only authorized users can perform specific actions on articles.

## Policy Overview

The `ArticlePolicy` is responsible for defining the authorization logic for the `Article` model. The policy is registered in `AuthServiceProvider` and is automatically applied using Laravel's `authorize` method or `@can` Blade directives.

### Policy Methods

The `ArticlePolicy` includes the following methods:

- **viewAny(User $user): bool**  
  Determines whether the user can view any articles.
  ```php
  public function viewAny(User $user): bool
  {
      return false;
  }
  ```

- **view(User $user, Article $article): bool**  
  Determines whether the user can view a specific article.
  ```php
  public function view(User $user, Article $article): bool
  {
      return false;
  }
  ```

- **create(User $user): bool**  
  Determines whether the user can create articles.
  ```php
  public function create(User $user): bool
  {
      return false;
  }
  ```

- **update(User $user, Article $article): bool**  
  Ensures that only the owner of an article can edit it.
  ```php
  public function update(User $user, Article $article): bool
  {
      return $user->id === $article->user_id;
  }
  ```

- **delete(User $user, Article $article): bool**  
  Restricts article deletion.
  ```php
  public function delete(User $user, Article $article): bool
  {
      return false;
  }
  ```

- **restore(User $user, Article $article): bool**  
  Prevents article restoration.
  ```php
  public function restore(User $user, Article $article): bool
  {
      return false;
  }
  ```

- **forceDelete(User $user, Article $article): bool**  
  Prevents permanent deletion.
  ```php
  public function forceDelete(User $user, Article $article): bool
  {
      return false;
  }
  ```

## Applying Policies in Controllers

In controllers, policies are applied using:

```php
use Illuminate\Support\Facades\Route;
use Modules\PkgBlog\App\Controllers\ArticleController;

Route::prefix('blog')->group(function () {
    Route::resource('articles', ArticleController::class);
});
```

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
