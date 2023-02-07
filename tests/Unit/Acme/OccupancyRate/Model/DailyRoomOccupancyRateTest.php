<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Model;

use Acme\OccupancyRate\Model\DailyRoomOccupancyRate;
use Acme\Room\Model\Room;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class DailyRoomOccupancyRateTest extends TestCase
{
    public function testCreate(): void
    {
        $room = new Room();
        $date = now()->startOfDay();

        $rate = DailyRoomOccupancyRate::create($room, $date);

        $this->assertEquals($room->capacity, $rate->totalCapacity);
        $this->assertEquals($room, $rate->room);
        $this->assertEquals($date, $rate->date);
    }
}
