<?php

declare(strict_types=1);

namespace Acme\Booking\Service\Block;

use Acme\Booking\Model\Block;
use Acme\Room\Model\Room;
use Carbon\Carbon;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BlockCreator
{
    public function create(Room $room, Carbon $startsAt, Carbon $endsAt): Block
    {
        return Block::create(
            room    : $room,
            startsAt: $startsAt,
            endsAt  : $endsAt,
        );
    }
}
