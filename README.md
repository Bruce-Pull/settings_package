## Laravel Settings Package

A dynamic settings system for Laravel with multi-level support.

---

## Installation

Follow these steps to install and configure the Laravel Settings Package:

### Step 1: Clone the Package

Clone the `settings-package` repository into the same directory that contains your Laravel project(s).

**Example folder structure:**

```
Projects/
├── MyLaravelProject/
└── SettingsPackage/
```

---

### Step 2: Register the Local Package in Composer

Edit your Laravel project’s `composer.json` file and add a local repository reference:

```json
"repositories": [
  {
    "type": "path",
    "url": "../SettingsPackage"
  }
]
```

Then, install the package via Composer:

```bash
composer require bruce-pull/settings-package:dev-main
```

---

## Configuration

### Step 3: Publish the Configuration File

```bash
php artisan vendor:publish --provider="TuUsuario\LaravelSettings\Providers\SettingsServiceProvider" --tag="settings-config"
```

This file contains the default settings structure. You can freely add, edit, or delete settings according to your needs.

---

### Step 4: Publish the Migrations

```bash
php artisan vendor:publish --provider="TuUsuario\LaravelSettings\Providers\SettingsServiceProvider" --tag="settings-migrations"
```

---

### Step 5: Move Tenant Migration File

Move the generated migration file to the appropriate tenant migration folder if you're using multi-tenancy:

```bash
cp database/migrations/tenant/create_settings_table.php database/migrations/tenant/create_settings_table.php
```

---

### Step 6: Run the Migrations

```bash
php artisan migrate
```

---

## Usage

### Using the Facade

```php
use TuUsuario\LaravelSettings\Facades\Settings;

// Get a value
$theme = Settings::get('theme');

// Set a value
Settings::setSetting('theme', 'dark', SettingLevel::USER, 1);
```

### Using the Helper

```php
// Get a value with default
$language = setting('language', 'es');

// Get the settings service to access to any of its methods
$service = setting()->getProcessedSettings();
```

---

## Important Notes

* In a multi-tenant context, use Redis or another cache store that supports tagging:

```
CACHE_STORE=redis
```

* Don’t forget to move the published migration file to the tenant migrations folder to ensure correct schema setup for each tenant.

---

## License

MIT License
