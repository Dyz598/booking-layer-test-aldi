<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Contract;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
interface RoomOccupancyStatusChangesContract
{
    /**
     * @return RoomOccupancyStatusChanges[]
     */
    public function getChanges(): array;
}
