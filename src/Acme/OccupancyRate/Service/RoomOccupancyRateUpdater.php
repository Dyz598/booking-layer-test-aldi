<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Service;

use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChangesContract;
use Acme\OccupancyRate\Contract\RoomOccupancyRateUpdaterInterface;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomOccupancyRateUpdater
{
    /**
     * @param RoomOccupancyRateUpdaterInterface[] $roomOccupancyRateUpdaters
     */
    public function __construct(
        protected array $roomOccupancyRateUpdaters
    ) {}

    public function update(RoomOccupancyStatusChanges $changes): void
    {
        foreach ($this->roomOccupancyRateUpdaters as $roomOccupancyRateUpdater) {
            $roomOccupancyRateUpdater->update($changes);
        }
    }
}
