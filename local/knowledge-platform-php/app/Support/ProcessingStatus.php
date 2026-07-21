<?php

namespace App\Support;

/**
 * Port of BAL/Enums/ProcessingStatus.cs.
 */
final class ProcessingStatus
{
    public const PROCESSING = 0;
    public const PROCESSED = 1;
    public const PARTIALLY_PROCESSED = 2;
    public const FAILED = 3;
}
