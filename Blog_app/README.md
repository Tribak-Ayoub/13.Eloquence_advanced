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

