# Filament Reverb Doctor

A beautiful Filament 3.x plugin that provides a visual dashboard for diagnosing Laravel Reverb WebSocket configuration issues.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bitsoftsolutions/filament-reverb-doctor.svg?style=flat-square)](https://packagist.org/packages/bitsoftsolutions/filament-reverb-doctor)
[![Total Downloads](https://img.shields.io/packagist/dt/bitsoftsolutions/filament-reverb-doctor.svg?style=flat-square)](https://packagist.org/packages/bitsoftsolutions/filament-reverb-doctor)
[![License](https://img.shields.io/packagist/l/bitsoftsolutions/filament-reverb-doctor.svg?style=flat-square)](https://packagist.org/packages/bitsoftsolutions/filament-reverb-doctor)

## Features

- **Visual Dashboard**: Beautiful Filament-native UI showing all diagnostic results
- **Dashboard Widget**: Quick health status widget for your Filament dashboard
- **10 Diagnostic Checks**: Comprehensive checks inherited from Laravel Reverb Doctor
- **Real-time Feedback**: Loading states and notifications for better UX
- **Fully Configurable**: Customize navigation, authorization, and appearance
- **Dark Mode Support**: Seamless integration with Filament's dark mode

## Diagnostic Checks

The plugin runs 10 comprehensive checks:

| Check | Description |
|-------|-------------|
| **Reverb Installed** | Verifies Laravel Reverb package is installed |
| **Environment Variables** | Checks all required REVERB_* env vars are set |
| **Config Published** | Ensures reverb.php config file exists |
| **Broadcasting Driver** | Validates BROADCAST_CONNECTION is set to 'reverb' |
| **App Config** | Checks Reverb app configuration (id, key, secret) |
| **Server Config** | Validates server host and port settings |
| **Queue Connection** | Ensures queue connection is properly configured |
| **SSL Configuration** | Checks SSL/TLS settings consistency |
| **Server Connectivity** | Tests actual connection to Reverb server |
| **Port Availability** | Verifies configured port is not blocked |

## Requirements

- PHP 8.1+
- Laravel 10.x, 11.x, or 12.x
- Filament 3.x
- Laravel Reverb (for meaningful diagnostics)

## Installation

Install the package via Composer:

```bash
composer require bitsoftsolutions/filament-reverb-doctor
```

The package will automatically install [laravel-reverb-doctor](https://github.com/bitsoftsol/laravel-reverb-doctor) as a dependency.

## Setup

### 1. Register the Plugin

Add the plugin to your Filament Panel Provider:

```php
// app/Providers/Filament/AdminPanelProvider.php

use Bitsoftsolutions\FilamentReverbDoctor\FilamentReverbDoctorPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configurations
        ->plugin(FilamentReverbDoctorPlugin::make());
}
```

### 2. Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag="filament-reverb-doctor-config"
```

## Usage

### Dashboard Page

Navigate to **Reverb Health** in your Filament admin panel sidebar (under the "System" group by default). Click the "Run Diagnostics" button to execute all checks.

### Dashboard Widget

The plugin automatically adds a compact widget to your Filament dashboard showing quick health status. Click "Run Check" to execute diagnostics or "View Details" to go to the full dashboard.

## Configuration

The published config file (`config/filament-reverb-doctor.php`) offers extensive customization:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'enabled' => true,           // Show/hide from navigation
        'group' => 'System',         // Navigation group (null for no group)
        'icon' => 'heroicon-o-heart', // Heroicon name
        'sort' => 100,               // Sort order in navigation
        'label' => 'Reverb Health',  // Navigation label
    ],

    /*
    |--------------------------------------------------------------------------
    | Widget
    |--------------------------------------------------------------------------
    */
    'widget' => [
        'enabled' => true,  // Show/hide dashboard widget
        'sort' => 10,       // Widget sort order
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */
    'authorization' => [
        // Require a specific permission
        'permission' => null, // e.g., 'view-reverb-doctor'

        // Use a gate for authorization
        'gate' => null, // e.g., 'viewReverbDoctor'

        // Custom callback
        'callback' => null, // e.g., [App\Policies\ReverbPolicy::class, 'viewDashboard']
    ],

    /*
    |--------------------------------------------------------------------------
    | Appearance
    |--------------------------------------------------------------------------
    */
    'appearance' => [
        'show_suggestions' => true, // Show fix suggestions column
        'show_details' => true,     // Show technical details
    ],
];
```

## Authorization Examples

### Using Permissions (Spatie Permission)

```php
// config/filament-reverb-doctor.php
'authorization' => [
    'permission' => 'view-reverb-diagnostics',
],
```

### Using Gates

```php
// app/Providers/AuthServiceProvider.php
Gate::define('viewReverbDoctor', function ($user) {
    return $user->isAdmin();
});

// config/filament-reverb-doctor.php
'authorization' => [
    'gate' => 'viewReverbDoctor',
],
```

### Using Custom Callback

```php
// config/filament-reverb-doctor.php
'authorization' => [
    'callback' => function () {
        return auth()->user()?->email === 'admin@example.com';
    },
],
```

## Screenshots

### Dashboard Page
*Full diagnostic dashboard showing all check results with status, messages, and suggestions.*

### Dashboard Widget
*Compact widget showing quick health status with run/re-run functionality.*

## CLI Alternative

For command-line diagnostics, check out [Laravel Reverb Doctor](https://github.com/bitsoftsol/laravel-reverb-doctor):

```bash
php artisan reverb:doctor
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email hafiz@bitsoftsolutions.com instead of using the issue tracker.

## Credits

- [Hafiz Siddiq](https://github.com/hafizsiddiq)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
