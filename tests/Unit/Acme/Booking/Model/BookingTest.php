<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Model;

use Acme\Booking\Model\Booking;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingTest extends TestCase
{
    public function testSuccess(): void
    {
        $room = Room::create(10);
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = $startsAt->copy()->addDays(2);

        $booking = Booking::create($room, $startsAt, $endsAt);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals($room, $booking->room);
        $this->assertEquals($startsAt, $booking->startsAt);
        $this->assertEquals($endsAt, $booking->endsAt);
    }
}
