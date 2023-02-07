<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Service;

use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Service\RoomOccupancyRateUpdater;
use Acme\Room\Model\Room;
use Mockery;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomOccupancyRateUpdaterTest extends TestCase
{
    public function testSuccess(): void
    {
        $updater = Mockery::mock('updater');
        $updater->shouldReceive('update')
            ->times(1);

        $rateUpdater = new RoomOccupancyRateUpdater([$updater]);

        $changes = new RoomOccupancyStatusChanges(new Room());
        $rateUpdater->update($changes);

        $this->assertTrue(true);
    }
}
