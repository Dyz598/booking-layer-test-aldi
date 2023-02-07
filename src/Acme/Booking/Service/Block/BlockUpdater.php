<?php

declare(strict_types=1);

namespace Acme\Booking\Service\Block;

use Acme\Booking\Model\Block;
use Acme\Room\Model\Room;
use Carbon\Carbon;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BlockUpdater
{
    public function update(Block $block, Room $room, Carbon $startsAt, Carbon $endsAt): Block
    {
        $block->startsAt = $startsAt;
        $block->endsAt = $endsAt;

        $block->room()->associate($room);

        return $block;
    }
}
