<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Service;

use Acme\OccupancyRate\Contract\CalculateOccupancyRate;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
final class OccupancyRateCalculator
{
    public function calculate(CalculateOccupancyRate $parameter): float
    {
        $occupancyRate = $parameter->getTotalBooked() / ($parameter->getTotalCapacity() - $parameter->getTotalBlocked());

        return round($occupancyRate, 2);
    }
}
