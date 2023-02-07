<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Repository;

use Acme\Booking\Model\Block;
use Acme\Booking\Repository\BlockRepository;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BlockRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testAllOverlapMethods(): void
    {
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = Carbon::createFromFormat('Y-m-d', '2023-01-05')->startOfDay();
        $room = Room::create(5);
        $room->save();

        $block = Block::create($room, $startsAt, $endsAt);
        $block->save();

        $block2 = Block::create($room, $startsAt, $endsAt);
        $block2->save();

        /** @var BlockRepository $repo */
        $repo = $this->app->make(BlockRepository::class);

        $this->assertEquals(1, $repo->countByRoomAndOverlapRightOuter(
            $room->id,
            Carbon::createFromFormat('Y-m-d', '2023-01-04')->startOfDay(),
            Carbon::createFromFormat('Y-m-d', '2023-01-07')->startOfDay(),
            $block->getKey()
        ));

        $this->assertEquals(1, $repo->countByRoomAndOverlapLeftOuter(
            $room->id,
            Carbon::createFromFormat('Y-m-d', '2022-12-31')->startOfDay(),
            Carbon::createFromFormat('Y-m-d', '2023-01-02')->startOfDay(),
            $block->getKey()
        ));

        $this->assertEquals(1, $repo->countByRoomAndOverlapsInnerDate(
            $room->id,
            Carbon::createFromFormat('Y-m-d', '2022-12-30')->startOfDay(),
            Carbon::createFromFormat('Y-m-d', '2023-01-07')->startOfDay(),
            $block->getKey()
        ));

        $this->assertEquals(1, $repo->countByRoomAndOverlapsOuterDate(
            $room->id,
            Carbon::createFromFormat('Y-m-d', '2023-01-02')->startOfDay(),
            Carbon::createFromFormat('Y-m-d', '2023-01-04')->startOfDay(),
            $block->getKey()
        ));
    }
}
