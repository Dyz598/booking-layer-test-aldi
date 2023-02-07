<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Event\Booking;

use Acme\Booking\Event\Booking\BookingCreated;
use Acme\Booking\Model\Booking;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingCreatedTest extends TestCase
{
    public function testSuccess(): void
    {
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = $startsAt->copy()->addDays(5);
        $room = Room::create(10);

        $booking = new Booking();
        $booking->startsAt = $startsAt;
        $booking->endsAt = $endsAt;
        $booking->room()->associate($room);

        $event = BookingCreated::createFromBooking($booking);

        $this->assertEquals($room, $event->getRoom());
        $this->assertEquals($startsAt, $event->getStartsAt());
        $this->assertEquals($endsAt, $event->getEndsAt());
        $this->assertCount(1, $event->getChanges());
        $this->assertInstanceOf(RoomOccupancyStatusChanges::class, $event->getChanges()[0]);
    }
}
