<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    |
    | Configure how the Reverb Doctor appears in Filament navigation.
    |
    */
    'navigation' => [
        'enabled' => true,
        'group' => 'System',
        'icon' => 'heroicon-o-heart',
        'sort' => 100,
        'label' => 'Reverb Health',
    ],

    /*
    |--------------------------------------------------------------------------
    | Widget
    |--------------------------------------------------------------------------
    |
    | Configure the dashboard widget settings.
    |
    */
    'widget' => [
        'enabled' => true,
        'sort' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    |
    | Control who can access the Reverb Doctor dashboard.
    | Set to null to allow all authenticated users.
    |
    */
    'authorization' => [
        // Permission required to view the dashboard (null = no permission check)
        'permission' => null,

        // Gate ability to check (null = no gate check)
        'gate' => null,

        // Custom callback for authorization (null = no custom check)
        // Example: 'callback' => \App\Policies\ReverbDoctorPolicy::class . '@canView',
        'callback' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Appearance
    |--------------------------------------------------------------------------
    |
    | Customize the appearance of the dashboard.
    |
    */
    'appearance' => [
        'show_suggestions' => true,
        'show_details' => true,
    ],
];
