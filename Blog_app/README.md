# Laravel Modular System with Blade - Blog Module CRUD Example

## **1. Introduction**
This tutorial will guide you through setting up a modular Laravel system using Blade, focusing on the Blog module. We'll implement a full CRUD system for articles, including comments and tags.

## **2. Setting Up the Modular System**

### **2.1. Creating the `modules/` Directory**
Run the following command to create a directory for modules:
```bash
mkdir modules
```

### **2.2. Autoloading Modules in `composer.json`**
Modify `composer.json` to recognize the `modules/` directory:
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Modules\\": "modules/"
    }
}
```
Then run:
```bash
composer dump-autoload
```

## **3. Blog Module Structure**
The Blog module will have the following structure:
```
/modules
  /Blog
    /App
      /Controllers
      /Models
      /Requests
      /Services
    /Database
      /Migrations
      /Seeders
    /Resources
      /Views
    /Routes
```

## **4. Creating the Blog Module**

### **4.1. Creating the Module Structure**
```bash
mkdir -p modules/Blog/App/Controllers
mkdir -p modules/Blog/App/Models
mkdir -p modules/Blog/App/Requests
mkdir -p modules/Blog/App/Services
mkdir -p modules/Blog/Database/Migrations
mkdir -p modules/Blog/Database/Seeders
mkdir -p modules/Blog/Resources/Views
mkdir -p modules/Blog/Routes
```

### **4.2. Registering the Blog Module Service Provider**
Create `modules/Blog/App/Providers/BlogServiceProvider.php`:
```php
namespace Modules\Blog\App\Providers;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(base_path('modules/Blog/Routes/web.php'));
        $this->loadViewsFrom(base_path('modules/Blog/Resources/Views'), 'blog');
    }
}
```

### **4.3. Loading Module Providers in `AppServiceProvider`**
Modify `AppServiceProvider.php` to load module providers:
```php
$this->app->register(\Modules\Blog\App\Providers\BlogServiceProvider::class);
```

## **5. Creating the Article CRUD System**

### **5.1. Creating the Model**
Create `modules/Blog/App/Models/Article.php`:
```php
namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content'];
}
```

### **5.2. Creating the Migration**
Run the command:
```bash
php artisan make:migration create_articles_table --path=modules/Blog/Database/Migrations
```
Then edit the migration file:
```php
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->timestamps();
});
```
Run migrations:
```bash
php artisan migrate --path=modules/Blog/Database/Migrations
```

### **5.3. Creating the Controller**
Create `modules/Blog/App/Controllers/ArticleController.php`:
```php
namespace Modules\Blog\App\Controllers;

use Illuminate\Http\Request;
use Modules\Blog\App\Models\Article;
use Illuminate\Routing\Controller;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view('blog::articles.index', compact('articles'));
    }

    public function create()
    {
        return view('blog::articles.create');
    }

    public function store(Request $request)
    {
        Article::create($request->all());
        return redirect()->route('blog.articles.index');
    }

    public function edit(Article $article)
    {
        return view('blog::articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());
        return redirect()->route('blog.articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('blog.articles.index');
    }
}
```

### **5.4. Defining Routes**
Create `modules/Blog/Routes/web.php`:
```php
use Modules\Blog\App\Controllers\ArticleController;

Route::prefix('blog')->name('blog.')->group(function() {
    Route::resource('articles', ArticleController::class);
});
```

### **5.5. Creating Views**
Create `modules/Blog/Resources/Views/articles/index.blade.php`:
```blade
@extends('layouts.app')

@section('content')
    <a href="{{ route('blog.articles.create') }}">Create Article</a>
    <ul>
        @foreach ($articles as $article)
            <li>{{ $article->title }}
                <a href="{{ route('blog.articles.edit', $article) }}">Edit</a>
                <form action="{{ route('blog.articles.destroy', $article) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
```

## **6. Running the Application**
Start the server:
```bash
php artisan serve
```
Visit `http://localhost:8000/blog/articles` to see your articles CRUD in action.
