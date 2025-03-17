# Laravel Blog with Modular System

This project implements a **modular Laravel-based blog**, focusing on modularity, authorization, validation, and testing. It includes features such as multi-language support, role-based access control (RBAC) through policies, custom validation with Form Requests, and tests to ensure the blog's functionality. The application is structured in a way that allows easy expansion and maintainability.

## Features

- **Modular System**: The project leverages Laravel's modular approach to split functionality into separate modules (e.g., Articles, Categories, Tags, etc.), making it scalable and maintainable.
- **Role-based Access Control (RBAC)**: User roles (Admin, Editor, etc.) control who can perform actions like creating, updating, or deleting articles, utilizing Laravel’s built-in policies.
- **Multi-language Support**: Supports multiple languages using Laravel's translation system.
- **Form Request Validation**: Ensures data integrity by validating form submissions via Laravel Form Requests.
- **Testing**: PHPUnit tests ensure that features like article creation, validation, and user authorization work as expected.

---

## Table of Contents

1. [Project Setup](#project-setup)
2. [Modular System Setup](#modular-system-setup)
3. [Translating Your Application](#translating-your-application)
4. [Article Policies](#article-policies)
5. [Form Request Validation](#form-request-validation)
6. [Testing the Blog](#testing-the-blog)
7. [Conclusion](#conclusion)

---

## Project Setup

### Requirements

- PHP 8.0 or higher
- Composer
- Laravel 11
- MySQL Database
- Node.js and NPM (for asset compilation with Vite)

---

## **Installation**

1. **Clone the Repository**

```bash
git clone https://github.com/yourusername/laravel-blog.git
cd laravel-blog
```

2. **Install Dependencies**

```bash
composer install
npm install
```

3. **Set Up the Environment**

Rename the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

4. **Configure the Database**

Open the `.env` file and update the database connection settings:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations to create the necessary database tables:

```bash
php artisan migrate
```

5. **Install Frontend Dependencies (Vite)**

```bash
npm run dev
```

---

## **Modular System Setup**

This application is built with a **modular system** in mind. The application is divided into independent modules, each handling its own functionality (e.g., blog articles, categories, tags).

### **Step 1: Create a Modular Folder Structure**

You can organize your project into modules by creating separate directories for each module (e.g., `PkgBlog`, `PkgCategory`, `PkgTag`). Each module will contain its own controllers, views, routes, models, and other resources.

Create a directory structure like this:

```
/Modules
    /PkgBlog
        /App
            /Controllers
            /Models
            /Views
            /Routes
        /Resources
            /lang
            /views
        /Providers
            PkgBlogServiceProvider.php
```

### **Step 2: Set Up the Service Providers**

Each module should have its own service provider to register module-specific resources like routes, views, and models.

For example, in the `PkgBlog` module, create a `PkgBlogServiceProvider` inside the `Modules/PkgBlog/Providers` folder:

```php
// Modules/PkgBlog/Providers/PkgBlogServiceProvider.php

namespace Modules\PkgBlog\Providers;

use Illuminate\Support\ServiceProvider;

class PkgBlogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register any bindings for this module (e.g., custom services)
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(module_path('PkgBlog', 'Routes/web.php'));

        // Load views for this module
        $this->loadViewsFrom(module_path('PkgBlog', 'Resources/views'), 'pkgblog');
    }
}
```


### **Step 3: Register the Service Providers**

To register the service providers in the `app.php` configuration, you need to add them in the `config/app.php` file:

```php
// config/app.php

'providers' => [
    // Other service providers...

    Modules\PkgBlog\Providers\PkgBlogServiceProvider::class,

],
```

This ensures that when Laravel bootstraps, it loads the modules’ routes and views.

### **Step 4: Define Routes for Each Module**

Each module can define its own routes within the `Routes/web.php` file. For example, the routes for the `PkgBlog` module:

```php
// Modules/PkgBlog/Routes/web.php

use Illuminate\Support\Facades\Route;
use Modules\PkgBlog\App\Controllers\ArticleController;

Route::prefix('blog')->group(function () {
    Route::resource('articles', ArticleController::class);
});
```


### **Step 5: Create the Controllers and Models for Each Module**

Each module should have its own controllers and models. For example, in the `PkgBlog` module, create the `ArticleController` and `Article` model:

```php
// Modules/PkgBlog/App/Controllers/ArticleController.php

namespace Modules\PkgBlog\App\Controllers;

use App\Http\Controllers\Controller;
use Modules\PkgBlog\App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view('pkgblog::articles.index', compact('articles'));
    }

    // Other methods for creating, storing, editing, and deleting articles
}
```

```php
// Modules/PkgBlog/App/Models/Article.php

namespace Modules\PkgBlog\App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'category_id'];
}
```

### **Step 6: Create Views for Each Module**

Create views for each module inside their respective `Resources/views` folders. For example:

```php
// Modules/PkgBlog/Resources/views/articles/index.blade.php

@extends('layouts.app')

@section('content')
    <h1>Articles</h1>
    @foreach ($articles as $article)
        <div>{{ $article->title }}</div>
    @endforeach
@endsection
```


### **Step 7: Autoload the Modules**

To autoload the modules, ensure that the `composer.json` file is configured to load them automatically. Add the `autoload` section like this:

```json
"autoload": {
    "psr-4": {
        "Modules\\": "modules",
    }
}
```

After updating the `composer.json` file, run the following command to regenerate the autoload files:

```bash
composer dump-autoload
```

### **Step 8: Testing the Modular System**

Now that all modules are set up, you should test if the routes, controllers, and views are properly working. You can do this by visiting the routes you've defined in your browser and ensuring everything is functioning correctly.

---

## **Translating Your Application**

Laravel comes with a powerful translation system that allows you to display content in different languages.

### 1. Install Translation Files

To add more languages, you can install the `laravel-lang/lang` package, which provides translations for Laravel validation messages and other system texts:

```bash
composer require laravel-lang/lang
```

Next, publish the translation files:

```bash
php artisan lang:install en fr  # This installs English and French language files
```

You can find the translations in `resources/lang/{lang}`.

### 2. Use Translations in Your Views

You can use the `__()` helper function to translate text:

```php
// Blade template
{{ __('auth.failed') }}  <!-- Output: Authentication failed -->
{{ __('messages.welcome') }}  <!-- Output: Welcome message -->
```

Modify the `resources/lang/{lang}/messages.php` file to add your custom translations:

```php
// resources/lang/en/messages.php
return [
    'welcome' => 'Welcome to the blog!',
];

// resources/lang/fr/messages.php
return [
    'welcome' => 'Bienvenue sur le blog!',
];
```

---

## **Article Policies in Laravel**

### **Overview of Policies**

Policies are used to authorize actions on models. In this blog project, the `ArticlePolicy` governs what actions users can perform on articles, such as creating, updating, and deleting.

### **Policy Methods**

The `ArticlePolicy` has methods for each action:

- **viewAny(User $user)**: Determines if a user can view any article.
- **view(User $user, Article $article)**: Determines if a user can view a specific article.
- **create(User $user)**: Determines if a user can create an article.
- **update(User $user, Article $article)**: Ensures only the owner of an article can edit it.
- **delete(User $user, Article $article)**: Prevents article deletion.
- **restore(User $user, Article $article)**: Prevents article restoration.
- **forceDelete(User $user, Article $article)**: Prevents permanent deletion.

### **Policy Code**

Here’s how the `ArticlePolicy` is defined:

```php
// app/Policies/ArticlePolicy.php
public function update(User $user, Article $article): bool
{
    return $user->id === $article->user_id;
}

public function delete(User $user, Article $article): bool
{
    return false;
}

public function restore(User $user, Article $article): bool
{
    return false;
}

public function forceDelete(User $user, Article $article): bool
{
    return false;
}
```

### **Applying Policies**

#### In Controllers:

You can use the `authorize` method to check permissions in controllers:

```php
public function update(Article $article)
{
    $this->authorize('update', $article);  // Checks if the user can update the article
    // Logic to update the article...
}
```

#### In Blade Views:

You can use the `@can` directive to conditionally display elements based on permissions:

```blade
@can('update', $article)
    <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-primary">Edit</a>
@endcan
```

---

## **Form Request Validation in Laravel**

Laravel uses **Form Request Validation** to validate incoming requests. This keeps controller methods clean and separates the validation logic.

### **Creating a Form Request**

You can create a custom Form Request using Artisan:

```bash
php artisan make:request ArticleRequest
```

Inside the generated `ArticleRequest.php` file, define the rules for validation:

```php
// app/Http/Requests/ArticleRequest.php
public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'array',
        'tags.*' => 'exists:tags,id',
    ];
}
```

Then, inject this request into your controller method:

```php
public function store(ArticleRequest $request)
{
    $article = Article::create($request->validated());
    return redirect()->route('articles.index')->with('success', 'Article created successfully.');
}
```

---

## **Testing the Blog**

Testing is crucial to ensure that your application works as expected. In this project, tests are written for article creation and validation.

### **Test Cases**

Here are some essential tests:

1. **Authenticated User Can Create Article**
   
   This test checks if an authenticated user can successfully create an article.

```php
public function test_authenticated_user_can_create_article()
{
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $tags = Tag::factory()->count(3)->create();

    $response = $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Test Article',
        'content' => 'This is a test article.',
        'category_id' => $category->id,
        'tags' => $tags->pluck('id')->toArray(),
    ]);

    $response->assertStatus(302);  // Successful redirect
    $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
}
```

2. **Guest Cannot Create Article**

   Ensures that a guest is redirected to the login page and cannot create an article.

```php
public function test_guest_cannot_create_article()
{
    $response = $this->post(route('articles.store'), [
        'title' => 'Guest Article',
        'content' => 'Should not be created.',
    ]);

    $response->assertRedirect(route('login'));
    $this->assertDatabaseMissing('articles', ['title' => 'Guest Article']);
}
```

3. **Article Creation Requires Validation**

   Verifies that the application will trigger validation errors if required fields are missing.

```php
public function test_article_creation_requires_validation()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('articles.store'), [
        'title' => '', // Invalid title
        'content' => '', // Invalid content
        'category_id' => null, // Invalid category
        'tags' => [], // Invalid tags
    ]);

    $response->assertSessionHasErrors(['title', 'content', 'category_id', 'tags']);
}
```

### **Running Tests**

You can run the tests using the following command:

```bash
php artisan test --filter=ArticleTest
```

This will run only the tests inside the `ArticleTest.php` file.

---

## **Conclusion**

This project demonstrates a clean, maintainable way to build a modular blog application using Laravel. The key features include:

- **Modular architecture** for scalability.
- **Role-based access control** to restrict who can perform certain actions on articles.
- **Form request validation** to ensure data integrity.
- **Testing** to verify functionality and user authorization.

By following the steps in this guide, you should have a solid foundation for building more complex applications with Laravel.
