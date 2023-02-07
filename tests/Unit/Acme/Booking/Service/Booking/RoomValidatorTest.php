<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Booking\Service\Booking;

use Acme\Booking\Model\Booking;
use Acme\Booking\Service\Booking\RoomValidator;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
final class RoomValidatorTest extends TestCase
{
    use RefreshDatabase;

    public function testReturnTrue(): void
    {
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = Carbon::createFromFormat('Y-m-d', '2023-01-05')->startOfDay();
        $room = Room::create(1);
        $room->save();

        /** @var RoomValidator $validator */
        $validator = $this->app->make(RoomValidator::class);

        $this->assertTrue($validator->doesRoomHaveCapacity($room, $startsAt, $endsAt));
    }

    public function testReturnFalse(): void
    {
        $startsAt = Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay();
        $endsAt = Carbon::createFromFormat('Y-m-d', '2023-01-05')->startOfDay();
        $room = Room::create(1);
        $room->save();

        $booking = Booking::create($room, $startsAt, $endsAt);
        $booking->save();

        /** @var RoomValidator $validator */
        $validator = $this->app->make(RoomValidator::class);

        $this->assertFalse($validator->doesRoomHaveCapacity($room, $startsAt, $endsAt));
    }
}
