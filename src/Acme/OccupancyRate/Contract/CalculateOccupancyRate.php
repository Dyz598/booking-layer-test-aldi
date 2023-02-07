<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Contract;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class CalculateOccupancyRate
{
    public function __construct(
        protected int $totalBooked,
        protected int $totalCapacity,
        protected int $totalBlocked,
    ) {}

    /**
     * @return int
     */
    public function getTotalBooked(): int
    {
        return $this->totalBooked;
    }

    /**
     * @return int
     */
    public function getTotalCapacity(): int
    {
        return $this->totalCapacity;
    }

    /**
     * @return int
     */
    public function getTotalBlocked(): int
    {
        return $this->totalBlocked;
    }
}
