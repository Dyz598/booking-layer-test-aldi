<?php

declare(strict_types=1);

namespace Acme\Booking\Service\Booking;

use Acme\Booking\Model\Booking;
use Acme\Room\Model\Room;
use Carbon\Carbon;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingUpdater
{
    public function update(Booking $booking, Room $room, Carbon $startsAt, Carbon $endsAt): Booking
    {
        $booking->startsAt = $startsAt;
        $booking->endsAt = $endsAt;

        $booking->room()->associate($room);

        return $booking;
    }
}
