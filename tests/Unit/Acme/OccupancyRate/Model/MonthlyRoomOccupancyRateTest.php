<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Model;

use Acme\OccupancyRate\Model\DailyRoomOccupancyRate;
use Acme\OccupancyRate\Model\MonthlyRoomOccupancyRate;
use Acme\Room\Model\Room;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class MonthlyRoomOccupancyRateTest extends TestCase
{
    public function testCreate(): void
    {
        $room = new Room();
        $date = now()->startOfMonth()->startOfDay();

        $rate = MonthlyRoomOccupancyRate::create($room, $date);

        $this->assertEquals($room->capacity * $date->daysInMonth, $rate->totalCapacity);
        $this->assertEquals($room, $rate->room);
        $this->assertEquals($date->month, $rate->month);
        $this->assertEquals($date->year, $rate->year);
        $this->assertEquals($date->format('Y-m'), $rate->monthYear);
    }
}
