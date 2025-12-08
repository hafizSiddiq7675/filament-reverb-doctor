<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Bitsoftsolutions\FilamentReverbDoctor\Pages\ReverbHealthDashboard;
use Bitsoftsolutions\FilamentReverbDoctor\Widgets\ReverbStatusWidget;

class FilamentReverbDoctorPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'filament-reverb-doctor';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                ReverbHealthDashboard::class,
            ])
            ->widgets([
                ReverbStatusWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
