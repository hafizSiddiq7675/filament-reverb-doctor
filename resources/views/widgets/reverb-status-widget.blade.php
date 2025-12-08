<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-{{ $this->getStatusColor() }}-100 dark:bg-{{ $this->getStatusColor() }}-900/20">
                    @if($this->getHealthStatus() === 'healthy')
                        <x-heroicon-o-check-circle class="w-8 h-8 text-success-600 dark:text-success-400" />
                    @elseif($this->getHealthStatus() === 'warning')
                        <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-warning-600 dark:text-warning-400" />
                    @elseif($this->getHealthStatus() === 'unhealthy')
                        <x-heroicon-o-x-circle class="w-8 h-8 text-danger-600 dark:text-danger-400" />
                    @else
                        <x-heroicon-o-question-mark-circle class="w-8 h-8 text-gray-600 dark:text-gray-400" />
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reverb Health</h3>
                    @if($hasRun)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $summary['pass'] ?? 0 }} passed,
                            {{ $summary['fail'] ?? 0 }} failed,
                            {{ $summary['warn'] ?? 0 }} warnings
                        </p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Click to run diagnostics</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                <x-filament::button
                    wire:click="runQuickCheck"
                    size="sm"
                    :color="$hasRun ? 'gray' : 'primary'"
                >
                    {{ $hasRun ? 'Re-run' : 'Run Check' }}
                </x-filament::button>
                <x-filament::button
                    tag="a"
                    href="{{ \Bitsoftsolutions\FilamentReverbDoctor\Pages\ReverbHealthDashboard::getUrl() }}"
                    size="sm"
                    color="gray"
                >
                    View Details
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
