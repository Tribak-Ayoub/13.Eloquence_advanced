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

=======
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
=======
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
$this->authorize('update', $article);
```
This ensures that only the article owner can update it.

## Applying Policies in Blade Views

In Blade templates, the `@can` directive is used to conditionally show UI elements:
```blade
@can('update', $article)
    <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-primary">Edit</a>
@endcan
```

## Conclusion

Laravel policies provide a clean way to handle authorization logic, ensuring users can only modify their own articles while restricting access to other users.
