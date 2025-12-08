<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor\DTOs;

use Bitsoftsolutions\FilamentReverbDoctor\Enums\CheckStatus;

final class DiagnosticResult
{
    public function __construct(
        public readonly string $name,
        public readonly CheckStatus $status,
        public readonly string $message,
        public readonly ?string $suggestion = null,
        public readonly array $details = [],
    ) {}

    public static function fromCliResult(\Bitsoftsolutions\LaravelReverbDoctor\Results\DiagnosticResult $cliResult): self
    {
        $status = match ($cliResult->status->value) {
            'pass' => CheckStatus::PASS,
            'fail' => CheckStatus::FAIL,
            'warn' => CheckStatus::WARN,
            'skip' => CheckStatus::SKIP,
            default => CheckStatus::SKIP,
        };

        return new self(
            name: $cliResult->name,
            status: $status,
            message: $cliResult->message,
            suggestion: $cliResult->suggestion,
            details: $cliResult->details,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'status' => $this->status->value,
            'message' => $this->message,
            'suggestion' => $this->suggestion,
            'details' => $this->details,
        ];
    }
}
