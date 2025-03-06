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

