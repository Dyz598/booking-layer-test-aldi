<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Event\Block;

use Acme\Booking\Event\Block\BlockCreated;
use Acme\Booking\Event\Block\BlockUpdated;
use Acme\Booking\Model\Block;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BlockUpdatedTest extends TestCase
{
    public function testSuccess(): void
    {
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = $startsAt->copy()->addDays(5);
        $newStartsAt = Carbon::createFromFormat('Y-m-d', '2023-01-10')->startOfDay();
        $newEndsAt = $startsAt->copy()->addDays(10);
        $room = Room::create(10);
        $newRoom = Room::create(5);

        $block = new Block();
        $block->startsAt = $startsAt;
        $block->endsAt = $endsAt;
        $block->room()->associate($room);

        $newBlock = new Block();
        $newBlock->startsAt = $newStartsAt;
        $newBlock->endsAt = $newEndsAt;
        $newBlock->room()->associate($newRoom);

        $event = BlockUpdated::createFromBlock($newBlock, $block);

        $this->assertEquals($newRoom, $event->getRoom());
        $this->assertEquals($newStartsAt, $event->getStartsAt());
        $this->assertEquals($newEndsAt, $event->getEndsAt());

        $this->assertEquals($room, $event->getPreviousRoom());
        $this->assertEquals($startsAt, $event->getPreviousStartsAt());
        $this->assertEquals($endsAt, $event->getPreviousEndsAt());

        $this->assertCount(2, $event->getChanges());
        $this->assertInstanceOf(RoomOccupancyStatusChanges::class, $event->getChanges()[0]);
        $this->assertInstanceOf(RoomOccupancyStatusChanges::class, $event->getChanges()[1]);
    }
}
