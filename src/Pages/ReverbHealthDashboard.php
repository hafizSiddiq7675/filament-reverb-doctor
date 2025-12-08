<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
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
     * Results stored as arrays for Livewire compatibility
     * @var array<array{name: string, status: string, message: string, suggestion: ?string, details: array}>
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
        $dtoResults = $service->runAllChecks();

        // Convert DTOs to arrays for Livewire compatibility
        $this->results = array_map(fn ($result) => $result->toArray(), $dtoResults);
        $this->summary = $service->getSummary($dtoResults);
        $this->hasRun = true;

        $this->isLoading = false;

        // Send appropriate notification
        $failCount = $this->summary['fail'] ?? 0;
        $warnCount = $this->summary['warn'] ?? 0;
        $passCount = $this->summary['pass'] ?? 0;

        if ($failCount > 0) {
            Notification::make()
                ->title('Diagnostics Complete')
                ->body("{$failCount} check(s) failed. Review the suggestions below.")
                ->danger()
                ->duration(5000)
                ->send();
        } elseif ($warnCount > 0) {
            Notification::make()
                ->title('Diagnostics Complete')
                ->body("{$passCount} passed with {$warnCount} warning(s).")
                ->warning()
                ->duration(5000)
                ->send();
        } else {
            Notification::make()
                ->title('All checks passed!')
                ->body('Your Reverb configuration looks healthy.')
                ->success()
                ->duration(5000)
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
