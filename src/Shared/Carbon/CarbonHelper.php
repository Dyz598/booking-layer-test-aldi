<?php

declare(strict_types=1);

namespace Shared\Carbon;

use Carbon\Carbon;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
final class CarbonHelper
{
    /**
     * @param Carbon[] $carbonDates
     * @return Carbon[]
     */
    public static function getMonthYearsFromDates(array $carbonDates): array
    {
        $monthYears = [];

        foreach ($carbonDates as $carbonDate) {
            $monthYears[$carbonDate->format('Y-m')] = $carbonDate;
        }

        return $monthYears;
    }
}
