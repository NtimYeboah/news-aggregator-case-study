<?php

namespace App\Enums;

enum NewsRetrievalAttemptStatus: string
{
    case NOT_STARTED = 'not_started';
    case STARTED = 'started';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    public function notStarted(): bool
    {
        return $this->value === self::NOT_STARTED->value;
    }

    public function started(): bool
    {
        return $this->value === self::STARTED->value;
    }

    public function completed(): bool
    {
        return $this->value === self::COMPLETED->value;
    }

    public function failed(): bool
    {
        return $this->value === self::FAILED->value;
    }
}
