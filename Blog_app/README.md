
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
