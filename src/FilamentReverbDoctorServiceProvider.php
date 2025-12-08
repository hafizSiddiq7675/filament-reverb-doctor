<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor;

use Illuminate\Support\ServiceProvider;
use Bitsoftsolutions\FilamentReverbDoctor\Services\DiagnosticService;

class FilamentReverbDoctorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-reverb-doctor.php',
            'filament-reverb-doctor'
        );

        $this->app->singleton(DiagnosticService::class, function ($app) {
            return new DiagnosticService();
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-reverb-doctor');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/filament-reverb-doctor.php' => config_path('filament-reverb-doctor.php'),
            ], 'filament-reverb-doctor-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-reverb-doctor'),
            ], 'filament-reverb-doctor-views');
        }
    }
}
