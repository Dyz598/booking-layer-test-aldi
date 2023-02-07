<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Event\Block;

use Acme\Booking\Event\Block\BlockCreated;
use Acme\Booking\Model\Block;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BlockCreatedTest extends TestCase
{
    public function testSuccess(): void
    {
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = $startsAt->copy()->addDays(5);
        $room = Room::create(10);

        $block = new Block();
        $block->startsAt = $startsAt;
        $block->endsAt = $endsAt;
        $block->room()->associate($room);

        $event = BlockCreated::createFromBlock($block);

        $this->assertEquals($room, $event->getRoom());
        $this->assertEquals($startsAt, $event->getStartsAt());
        $this->assertEquals($endsAt, $event->getEndsAt());
        $this->assertCount(1, $event->getChanges());
        $this->assertInstanceOf(RoomOccupancyStatusChanges::class, $event->getChanges()[0]);
    }
}
