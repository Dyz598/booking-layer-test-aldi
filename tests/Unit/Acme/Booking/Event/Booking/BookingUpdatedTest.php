<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Event\Booking;

use Acme\Booking\Event\Booking\BookingUpdated;
use Acme\Booking\Model\Booking;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingUpdatedTest extends TestCase
{
    public function testSuccess(): void
    {
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = $startsAt->copy()->addDays(5);
        $newStartsAt = Carbon::createFromFormat('Y-m-d', '2023-01-10')->startOfDay();
        $newEndsAt = $startsAt->copy()->addDays(10);
        $room = Room::create(10);
        $newRoom = Room::create(5);

        $booking = new Booking();
        $booking->startsAt = $startsAt;
        $booking->endsAt = $endsAt;
        $booking->room()->associate($room);

        $newBooking = new Booking();
        $newBooking->startsAt = $newStartsAt;
        $newBooking->endsAt = $newEndsAt;
        $newBooking->room()->associate($newRoom);

        $event = BookingUpdated::createFromBooking($newBooking, $booking);

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
