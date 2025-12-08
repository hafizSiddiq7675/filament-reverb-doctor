<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor\Services;

use Bitsoftsolutions\FilamentReverbDoctor\DTOs\DiagnosticResult;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\BroadcastConnectionCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\ConfigConsistencyCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\ConnectionTestCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\DockerDetectionCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\EnvironmentVariablesCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\FrontendSyncCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\PortAvailabilityCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\QueueWorkerCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\ReverbProcessCheck;
use Bitsoftsolutions\LaravelReverbDoctor\Checks\SslCertificateCheck;

class DiagnosticService
{
    /**
     * @var array<class-string>
     */
    protected array $checks = [
        EnvironmentVariablesCheck::class,
        ConfigConsistencyCheck::class,
        BroadcastConnectionCheck::class,
        PortAvailabilityCheck::class,
        ReverbProcessCheck::class,
        SslCertificateCheck::class,
        QueueWorkerCheck::class,
        FrontendSyncCheck::class,
        DockerDetectionCheck::class,
        ConnectionTestCheck::class,
    ];

    /**
     * Run all diagnostic checks.
     *
     * @return array<DiagnosticResult>
     */
    public function runAllChecks(): array
    {
        $results = [];

        foreach ($this->checks as $checkClass) {
            $check = app($checkClass);
            $cliResult = $check->run();
            $results[] = DiagnosticResult::fromCliResult($cliResult);
        }

        return $results;
    }

    /**
     * Run a single diagnostic check by class name.
     */
    public function runCheck(string $checkClass): DiagnosticResult
    {
        $check = app($checkClass);
        $cliResult = $check->run();

        return DiagnosticResult::fromCliResult($cliResult);
    }

    /**
     * Get summary statistics from results.
     *
     * @param array<DiagnosticResult> $results
     */
    public function getSummary(array $results): array
    {
        $summary = [
            'total' => count($results),
            'pass' => 0,
            'fail' => 0,
            'warn' => 0,
            'skip' => 0,
        ];

        foreach ($results as $result) {
            $summary[$result->status->value]++;
        }

        return $summary;
    }

    /**
     * Check if all diagnostics passed.
     *
     * @param array<DiagnosticResult> $results
     */
    public function allPassed(array $results): bool
    {
        foreach ($results as $result) {
            if ($result->status->value === 'fail') {
                return false;
            }
        }

        return true;
    }

    /**
     * Get list of available check classes.
     *
     * @return array<class-string>
     */
    public function getCheckClasses(): array
    {
        return $this->checks;
    }
}
