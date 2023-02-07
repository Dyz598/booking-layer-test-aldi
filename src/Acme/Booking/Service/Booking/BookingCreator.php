<?php

declare(strict_types=1);

namespace Acme\Booking\Service\Booking;

use Acme\Booking\Model\Booking;
use Acme\Room\Model\Room;
use Carbon\Carbon;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingCreator
{
    public function create(Room $room, Carbon $startsAt, Carbon $endsAt): Booking
    {
        return Booking::create(
            room    : $room,
            startsAt: $startsAt,
            endsAt  : $endsAt,
        );
    }
}
