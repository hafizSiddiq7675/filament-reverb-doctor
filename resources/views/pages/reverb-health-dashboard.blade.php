<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Summary Cards --}}
        @if($hasRun)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-filament::section>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-success-600 dark:text-success-400">{{ $summary['pass'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Passed</div>
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-danger-600 dark:text-danger-400">{{ $summary['fail'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Failed</div>
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-warning-600 dark:text-warning-400">{{ $summary['warn'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Warnings</div>
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $summary['skip'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Skipped</div>
                    </div>
                </x-filament::section>
            </div>
        @endif

        {{-- Results Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Diagnostic Results
            </x-slot>

            @if(!$hasRun)
                <div class="text-center py-8">
                    <x-heroicon-o-play class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" />
                    <p class="text-gray-500 dark:text-gray-400">Click "Run Diagnostics" to check your Reverb configuration.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Status</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Check</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Message</th>
                                @if(config('filament-reverb-doctor.appearance.show_suggestions', true))
                                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Suggestion</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                                @php
                                    $statusColor = match($result['status']) {
                                        'pass' => 'success',
                                        'fail' => 'danger',
                                        'warn' => 'warning',
                                        'skip' => 'gray',
                                        default => 'gray',
                                    };
                                    $statusLabel = match($result['status']) {
                                        'pass' => 'Pass',
                                        'fail' => 'Fail',
                                        'warn' => 'Warning',
                                        'skip' => 'Skip',
                                        default => 'Unknown',
                                    };
                                @endphp
                                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="py-3 px-4">
                                        <x-filament::badge :color="$statusColor">
                                            {{ $statusLabel }}
                                        </x-filament::badge>
                                    </td>
                                    <td class="py-3 px-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ $result['name'] }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                                        {{ $result['message'] }}
                                    </td>
                                    @if(config('filament-reverb-doctor.appearance.show_suggestions', true))
                                        <td class="py-3 px-4 text-gray-500 dark:text-gray-500">
                                            {{ $result['suggestion'] ?? '-' }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
