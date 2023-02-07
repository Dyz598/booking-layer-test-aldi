<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Service;

use Acme\OccupancyRate\Contract\CalculateOccupancyRate;
use Acme\OccupancyRate\Service\OccupancyRateCalculator;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class OccupancyRateCalculatorTest extends TestCase
{
    public function testSuccess(): void
    {
        $calculator = new OccupancyRateCalculator();
        $result = $calculator->calculate(new CalculateOccupancyRate(
            totalBooked  : 4,
            totalCapacity: 12,
            totalBlocked : 1,
        ));

        $this->assertEquals($result, 0.36);
    }
}
