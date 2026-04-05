<?php

namespace App\Enum;

enum TelegramStatus: string
{
    case SENT = 'sent';
    case FAILED = 'failed';
    case SKIPPED = 'skipped';
}
