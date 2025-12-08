<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor\Widgets;

use Filament\Widgets\Widget;
use Bitsoftsolutions\FilamentReverbDoctor\DTOs\DiagnosticResult;
use Bitsoftsolutions\FilamentReverbDoctor\Services\DiagnosticService;

class ReverbStatusWidget extends Widget
{
    protected static string $view = 'filament-reverb-doctor::widgets.reverb-status-widget';

    protected static ?int $sort = 10;

    protected int | string | array $columnSpan = 'full';

    /**
     * @var array<DiagnosticResult>
     */
    public array $results = [];

    public array $summary = [];

    public bool $hasRun = false;

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
        $service = app(DiagnosticService::class);
        $this->results = $service->runAllChecks();
        $this->summary = $service->getSummary($this->results);
        $this->hasRun = true;
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
