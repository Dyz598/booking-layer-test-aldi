<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Model;

use Acme\Booking\Model\Block;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BlockTest extends TestCase
{
    public function testSuccess(): void
    {
        $room = Room::create(10);
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = $startsAt->copy()->addDays(2);

        $block = Block::create($room, $startsAt, $endsAt);

        $this->assertInstanceOf(Block::class, $block);
        $this->assertEquals($room, $block->room);
        $this->assertEquals($startsAt, $block->startsAt);
        $this->assertEquals($endsAt, $block->endsAt);
    }
}
