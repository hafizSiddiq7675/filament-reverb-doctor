<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Bitsoftsolutions\FilamentReverbDoctor\DTOs\DiagnosticResult;
use Bitsoftsolutions\FilamentReverbDoctor\Services\DiagnosticService;

class ReverbHealthDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static string $view = 'filament-reverb-doctor::pages.reverb-health-dashboard';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 100;

    protected static ?string $title = 'Reverb Health';

    protected static ?string $navigationLabel = 'Reverb Health';

    protected static ?string $slug = 'reverb-health';

    /**
     * @var array<DiagnosticResult>
     */
    public array $results = [];

    public array $summary = [];

    public bool $isLoading = false;

    public bool $hasRun = false;

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-reverb-doctor.navigation.enabled', true);
    }

    public function mount(): void
    {
        static::$navigationIcon = config('filament-reverb-doctor.navigation.icon', 'heroicon-o-heart');
        static::$navigationGroup = config('filament-reverb-doctor.navigation.group');
        static::$navigationSort = config('filament-reverb-doctor.navigation.sort', 100);
        static::$navigationLabel = config('filament-reverb-doctor.navigation.label', 'Reverb Health');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runDiagnostics')
                ->label('Run Diagnostics')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->action('runDiagnostics'),
        ];
    }

    public function runDiagnostics(): void
    {
        $this->isLoading = true;

        $service = app(DiagnosticService::class);
        $this->results = $service->runAllChecks();
        $this->summary = $service->getSummary($this->results);
        $this->hasRun = true;

        $this->isLoading = false;

        if ($service->allPassed($this->results)) {
            Notification::make()
                ->title('All checks passed!')
                ->success()
                ->send();
        } else {
            $failCount = $this->summary['fail'] ?? 0;
            Notification::make()
                ->title("{$failCount} check(s) failed")
                ->danger()
                ->send();
        }
    }

    public static function canAccess(): bool
    {
        $config = config('filament-reverb-doctor.authorization');

        // Check permission
        if ($permission = $config['permission'] ?? null) {
            if (!auth()->user()?->can($permission)) {
                return false;
            }
        }

        // Check gate
        if ($gate = $config['gate'] ?? null) {
            if (!\Illuminate\Support\Facades\Gate::allows($gate)) {
                return false;
            }
        }

        // Check custom callback
        if ($callback = $config['callback'] ?? null) {
            if (is_callable($callback)) {
                return call_user_func($callback);
            }
        }

        return true;
    }
}
