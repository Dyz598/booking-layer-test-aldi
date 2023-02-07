<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Repository;

use Acme\Booking\Model\Booking;
use Acme\Booking\Repository\BookingRepository;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testAllOverlapMethods(): void
    {
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = Carbon::createFromFormat('Y-m-d', '2023-01-05')->startOfDay();
        $room = Room::create(5);
        $room->save();

        $booking = Booking::create($room, $startsAt, $endsAt);
        $booking->save();

        $booking2 = Booking::create($room, $startsAt, $endsAt);
        $booking2->save();

        /** @var BookingRepository $repo */
        $repo = $this->app->make(BookingRepository::class);

        $this->assertEquals(1, $repo->countByRoomAndOverlapRightOuter(
            $room->id,
            Carbon::createFromFormat('Y-m-d', '2023-01-04')->startOfDay(),
            Carbon::createFromFormat('Y-m-d', '2023-01-07')->startOfDay(),
            $booking->getKey()
        ));

        $this->assertEquals(1, $repo->countByRoomAndOverlapLeftOuter(
            $room->id,
            Carbon::createFromFormat('Y-m-d', '2022-12-31')->startOfDay(),
            Carbon::createFromFormat('Y-m-d', '2023-01-02')->startOfDay(),
            $booking->getKey()
        ));

        $this->assertEquals(1, $repo->countByRoomAndOverlapsInnerDate(
            $room->id,
            Carbon::createFromFormat('Y-m-d', '2022-12-30')->startOfDay(),
            Carbon::createFromFormat('Y-m-d', '2023-01-07')->startOfDay(),
            $booking->getKey()
        ));

        $this->assertEquals(1, $repo->countByRoomAndOverlapsOuterDate(
            $room->id,
            Carbon::createFromFormat('Y-m-d', '2023-01-02')->startOfDay(),
            Carbon::createFromFormat('Y-m-d', '2023-01-04')->startOfDay(),
            $booking->getKey()
        ));
    }
}
