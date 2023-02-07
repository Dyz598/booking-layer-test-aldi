<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Service\Booking;

use Acme\Booking\Model\Booking;
use Acme\Booking\Service\Booking\BookingCreator;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingCreatorTest extends TestCase
{
    public function testSuccess(): void
    {
        $room = Room::create(10);
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = $startsAt->copy()->addDays(2);

        /** @var BookingCreator $creator */
        $creator = $this->app->make(BookingCreator::class);

        $booking = $creator->create($room, $startsAt, $endsAt);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals($room, $booking->room);
        $this->assertEquals($startsAt, $booking->startsAt);
        $this->assertEquals($endsAt, $booking->endsAt);
    }
}
