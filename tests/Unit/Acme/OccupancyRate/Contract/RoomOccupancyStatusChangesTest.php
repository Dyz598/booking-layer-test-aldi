<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Contract;

use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\Room\Model\Room;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomOccupancyStatusChangesTest extends TestCase
{
    public function testPayload(): void
    {
        $room = new Room();
        $bookingDates = [now(), now()->addDay()];
        $cancelledBookingDates = [now()->subDay()];
        $blockDates = [now()->addWeek(), now()->addWeek()->addDay()];
        $cancelledBlockDates = [now()->addWeek()->subDay()];

        $changes = new RoomOccupancyStatusChanges(
            room                 : $room,
            bookingDates         : $bookingDates,
            blockDates           : $blockDates,
            cancelledBookingDates: $cancelledBookingDates,
            cancelledBlockDates  : $cancelledBlockDates,
        );

        $this->assertEquals($room, $changes->getRoom());
        $this->assertCount(2, $changes->getBookingDates());
        $this->assertCount(1, $changes->getCancelledBookingDates());
        $this->assertCount(2, $changes->getBlockDates());
        $this->assertCount(1, $changes->getCancelledBlockDates());
    }
}
