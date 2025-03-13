### **Laravel Modular System Setup Instructions**

These instructions guide you on how to set up a modular system in your Laravel application. This modular system will allow you to better organize your application by grouping related features into separate modules, making it easier to scale, maintain, and manage.

---

## Prerequisites

- Laravel 8 or later
- Composer installed
- PHP 7.3 or later

---

## Steps to Set Up the Modular System

### **1. Create the Modules Directory**

Create a `modules` directory in the root of your Laravel project. This will be the parent directory where all your modules will reside.

```bash
mkdir modules
```

### **2. Autoload Modules and Helpers**

To ensure that your modules and helpers are automatically loaded, you need to update the `composer.json` file. Add the `Modules` namespace and autoload helper files.

Open `composer.json` and update the `autoload` section:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Modules\\": "modules/"
    }
}
```

Then, run the following command to update Composer's autoloader:

```bash
composer dump-autoload
```

### **3. Create a Module (e.g., `PkgBlog`)**

Now, create the directory structure for your first module under the `modules` directory. For example, to create the `PkgBlog` module, the structure should look like this:

```
/modules
  /PkgBlog
    /App
      /Controllers
      /Models
      /Providers
    /Database
      /Migrations
      /Seeders
    /Resources
      /Views
    /Routes
```

### **4. Create the Service Provider for Your Module**

Each module should have its own service provider to register the module's resources such as routes, views, and migrations. 

#### **4.1 Core Module (CoreServiceProvider.php)**
In the Core module, create the `CoreServiceProvider.php` file inside `Modules/Core/App/Providers`.

```php
namespace Modules\Core\App\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register any services specific to the core module here.
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/Migrations');
        
        // Load routes from each file inside the Routes directory
        foreach (glob(__DIR__ . '/../../Routes/*.php') as $routeFile) {
            $this->loadRoutesFrom($routeFile);
        }

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../Resources/Views', 'Core');
    }
}
```

#### **4.2 Blog Module (PkgBlogServiceProvider.php)**
In the PkgBlog module, create the `PkgBlogServiceProvider.php` file inside `Modules/PkgBlog/App/Providers`.

```php
namespace Modules\PkgBlog\App\Providers;

use Illuminate\Support\ServiceProvider;

class PkgBlogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register any services specific to the blog module here.
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/Migrations');
        
        // Load routes from each file inside the Routes directory
        foreach (glob(__DIR__ . '/../../Routes/*.php') as $routeFile) {
            $this->loadRoutesFrom($routeFile);
        }

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../Resources/Views', 'PkgBlog');
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

### **7. Create Controllers and Views for the Module**

In the `Controllers` directory of your module, create a controller to handle the logic for the module. For example, the `ArticleController.php` for managing blog articles.

```php
namespace Modules\PkgBlog\App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PkgBlog\App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view('PkgBlog::articles.index', compact('articles'));
    }

    public function create()
    {
        return view('PkgBlog::articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Article::create($request->all());
        return redirect()->route('blog.index');
    }
}
```

Create views under `modules/PkgBlog/Resources/Views/`. For example, create `index.blade.php` to display the list of articles.

```blade
<h1>Blog Articles</h1>
<a href="{{ route('blog.create') }}">Add New Article</a>
@if($articles->isNotEmpty())
    @foreach ($articles as $article)
        <h2>{{ $article->title }}</h2>
        <p>{{ $article->content }}</p>
    @endforeach
@else
    <p>No articles found</p>
@endif
```

### **8. Create Models and Migrations**

Create models and migrations for your module. For the `PkgBlog` module, you need a model for `Article` and a migration file for creating the necessary table in the database.

Create the `Article` model inside `modules/PkgBlog/App/Models/Article.php`:

```php
namespace Modules\PkgBlog\App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content'];
}
```

Create the migration file inside `modules/PkgBlog/Database/Migrations/` to create the `articles` table:

```php
public function up()
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->timestamps();
    });
}
```

### **9. Seed Data for the Module**

If you'd like to seed data for your module, create a seeder class inside `modules/PkgBlog/Database/Seeders/ArticleSeeder.php`.

```php
namespace Modules\PkgBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgBlog\App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        Article::create(['title' => 'Sample Article', 'content' => 'This is a sample article.']);
    }
}
```

### **10. Run Migrations and Seed Data**

Run the migrations to create the `articles` table and optionally run the seeders to insert sample data:

```bash
php artisan migrate
php artisan db:seed --class=Modules\\PkgBlog\\Database\\Seeders\\ArticleSeeder
```

### **11. Access the Module**

To access the routes and views of your module, navigate to `/blog` in your browser. You should be able to view the articles, create new ones, and interact with your module.

---

## Conclusion

By following these steps, you have successfully set up a modular system in Laravel that organizes each feature into its own module. This will help you maintain a clean and scalable codebase as your application grows.

---

### Key Instructions:

- **Step 1:** Create the `modules` directory.
- **Step 2:** Update `composer.json` to autoload modules.
- **Step 3:** Create a module structure.
- **Step 4:** Create a service provider for each module to load migrations, routes, and views.
- **Step 5:** Define module-specific routes.
- **Step 6:** Implement controllers and views for the module.
- **Step 7:** Set up models and database migrations.
- **Step 8:** Optionally, seed data for the module.
- **Step 9:** Run migrations and seed data.
- **Step 10:** Access your module in the browser.