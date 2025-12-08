<?php

declare(strict_types=1);

namespace Bitsoftsolutions\FilamentReverbDoctor\Enums;

enum CheckStatus: string
{
    case PASS = 'pass';
    case FAIL = 'fail';
    case WARN = 'warn';
    case SKIP = 'skip';

    public function getColor(): string
    {
        return match ($this) {
            self::PASS => 'success',
            self::FAIL => 'danger',
            self::WARN => 'warning',
            self::SKIP => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PASS => 'heroicon-o-check-circle',
            self::FAIL => 'heroicon-o-x-circle',
            self::WARN => 'heroicon-o-exclamation-triangle',
            self::SKIP => 'heroicon-o-minus-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::PASS => 'PASS',
            self::FAIL => 'FAIL',
            self::WARN => 'WARN',
            self::SKIP => 'SKIP',
        };
    }
}
