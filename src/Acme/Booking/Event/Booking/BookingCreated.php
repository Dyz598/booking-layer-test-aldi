<?php

declare(strict_types=1);

namespace Acme\Booking\Event\Booking;

use Acme\Booking\Model\Booking;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChangesContract;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingCreated implements RoomOccupancyStatusChangesContract
{
    public function __construct(
        protected Room   $room,
        protected Carbon $startsAt,
        protected Carbon $endsAt,
    ) {}

    public static function createFromBooking(Booking $booking): static
    {
        return new static($booking->room, $booking->startsAt, $booking->endsAt);
    }

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @return Carbon
     */
    public function getStartsAt(): Carbon
    {
        return $this->startsAt;
    }

    /**
     * @return Carbon
     */
    public function getEndsAt(): Carbon
    {
        return $this->endsAt;
    }

    public function getChanges(): array
    {
        return [
            new RoomOccupancyStatusChanges(
                room        : $this->room,
                bookingDates: CarbonPeriod::create(
                    $this->startsAt->toDateString(),
                    $this->endsAt->toDateString()
                )->toArray(),
            )
        ];
    }
}
