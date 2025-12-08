<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor\Widgets;

use Filament\Widgets\Widget;
use Filament\Notifications\Notification;
use Bitsoftsolutions\FilamentReverbDoctor\Services\DiagnosticService;

class ReverbStatusWidget extends Widget
{
    protected static string $view = 'filament-reverb-doctor::widgets.reverb-status-widget';

    protected static ?int $sort = 10;

    protected int | string | array $columnSpan = 'full';

    /**
     * Results stored as arrays for Livewire compatibility
     */
    public array $results = [];

    public array $summary = [];

    public bool $hasRun = false;

    public bool $isLoading = false;

    public static function canView(): bool
    {
        return config('filament-reverb-doctor.widget.enabled', true);
    }

    public function mount(): void
    {
        static::$sort = config('filament-reverb-doctor.widget.sort', 10);
    }

    public function runQuickCheck(): void
    {
        $this->isLoading = true;

        $service = app(DiagnosticService::class);
        $dtoResults = $service->runAllChecks();

        // Convert DTOs to arrays for Livewire compatibility
        $this->results = array_map(fn ($result) => $result->toArray(), $dtoResults);
        $this->summary = $service->getSummary($dtoResults);
        $this->hasRun = true;

        $this->isLoading = false;

        // Send notification
        $failCount = $this->summary['fail'] ?? 0;
        $warnCount = $this->summary['warn'] ?? 0;

        if ($failCount > 0) {
            Notification::make()
                ->title('Reverb Health Check')
                ->body("{$failCount} check(s) failed. View details for more information.")
                ->danger()
                ->send();
        } elseif ($warnCount > 0) {
            Notification::make()
                ->title('Reverb Health Check')
                ->body("All checks passed with {$warnCount} warning(s).")
                ->warning()
                ->send();
        } else {
            Notification::make()
                ->title('Reverb Health Check')
                ->body('All checks passed successfully!')
                ->success()
                ->send();
        }
    }

    public function getHealthStatus(): string
    {
        if (!$this->hasRun) {
            return 'unknown';
        }

        if (($this->summary['fail'] ?? 0) > 0) {
            return 'unhealthy';
        }

        if (($this->summary['warn'] ?? 0) > 0) {
            return 'warning';
        }

        return 'healthy';
    }

    public function getStatusColor(): string
    {
        return match ($this->getHealthStatus()) {
            'healthy' => 'success',
            'warning' => 'warning',
            'unhealthy' => 'danger',
            default => 'gray',
        };
    }

    public function getStatusIcon(): string
    {
        return match ($this->getHealthStatus()) {
            'healthy' => 'heroicon-o-check-circle',
            'warning' => 'heroicon-o-exclamation-triangle',
            'unhealthy' => 'heroicon-o-x-circle',
            default => 'heroicon-o-question-mark-circle',
        };
    }
}
