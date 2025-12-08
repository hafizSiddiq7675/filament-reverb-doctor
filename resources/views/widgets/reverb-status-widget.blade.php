<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-{{ $this->getStatusColor() }}-100 dark:bg-{{ $this->getStatusColor() }}-900/20">
                    @if($isLoading)
                        <x-filament::loading-indicator class="w-8 h-8" />
                    @elseif($this->getHealthStatus() === 'healthy')
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
                    @if($isLoading)
                        <p class="text-sm text-gray-500 dark:text-gray-400">Running diagnostics...</p>
                    @elseif($hasRun)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-success-600 dark:text-success-400 font-medium">{{ $summary['pass'] ?? 0 }} passed</span>,
                            <span class="text-danger-600 dark:text-danger-400 font-medium">{{ $summary['fail'] ?? 0 }} failed</span>,
                            <span class="text-warning-600 dark:text-warning-400 font-medium">{{ $summary['warn'] ?? 0 }} warnings</span>
                        </p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Click to run diagnostics</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                <x-filament::button
                    wire:click="runQuickCheck"
                    wire:loading.attr="disabled"
                    wire:target="runQuickCheck"
                    size="sm"
                    :color="$hasRun ? 'gray' : 'primary'"
                >
                    <span wire:loading.remove wire:target="runQuickCheck">
                        {{ $hasRun ? 'Re-run' : 'Run Check' }}
                    </span>
                    <span wire:loading wire:target="runQuickCheck">
                        Running...
                    </span>
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
