<?php

declare(strict_types=1);

namespace Acme\Booking\Service\Booking;

use Acme\Booking\Repository\BlockRepository;
use Acme\Booking\Repository\BookingRepository;
use Acme\Room\Model\Room;
use Carbon\Carbon;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomValidator
{
    public function __construct(
        private BookingRepository $bookingRepository,
        private BlockRepository   $blockRepository,
    ) {}

    public function doesRoomHaveCapacity(Room $room, Carbon $startsAt, Carbon $endsAt, ?int $excludeBookingId = null, ?int $excludeBlockId = null): bool
    {
        $totalBookingsThatOverlapsInnerDates = $this->bookingRepository->countByRoomAndOverlapsInnerDate($room->id, $startsAt, $endsAt, $excludeBookingId);
        $totalBookingsThatOverlapsOuterDates = $this->bookingRepository->countByRoomAndOverlapsOuterDate($room->id, $startsAt, $endsAt, $excludeBookingId);
        $totalBookingsThatOverlapsLeftOuterDates = $this->bookingRepository->countByRoomAndOverlapLeftOuter($room->id, $startsAt, $endsAt, $excludeBookingId);
        $totalBookingsThatOverlapsRightOuterDates = $this->bookingRepository->countByRoomAndOverlapRightOuter($room->id, $startsAt, $endsAt, $excludeBookingId);

        $totalBlocksThatOverlapsInnerDates = $this->blockRepository->countByRoomAndOverlapsInnerDate($room->id, $startsAt, $endsAt, $excludeBlockId);
        $totalBlocksThatOverlapsOuterDates = $this->blockRepository->countByRoomAndOverlapsOuterDate($room->id, $startsAt, $endsAt, $excludeBlockId);
        $totalBlocksThatOverlapsLeftOuterDates = $this->blockRepository->countByRoomAndOverlapLeftOuter($room->id, $startsAt, $endsAt, $excludeBlockId);
        $totalBlocksThatOverlapsRightOuterDates = $this->blockRepository->countByRoomAndOverlapRightOuter($room->id, $startsAt, $endsAt, $excludeBlockId);

        if (
            ($room->capacity - $totalBlocksThatOverlapsInnerDates) === $totalBookingsThatOverlapsInnerDates ||
            ($room->capacity - $totalBlocksThatOverlapsOuterDates) === $totalBookingsThatOverlapsOuterDates ||
            ($room->capacity - $totalBlocksThatOverlapsLeftOuterDates) === $totalBookingsThatOverlapsLeftOuterDates ||
            ($room->capacity - $totalBlocksThatOverlapsRightOuterDates) === $totalBookingsThatOverlapsRightOuterDates
        ) {
            return false;
        }

        return true;
    }
}
