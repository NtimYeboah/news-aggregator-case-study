<?php

namespace App\Enums;

enum NewsRetrievalEventStatus: string
{
    case STARTED = 'started';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

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
