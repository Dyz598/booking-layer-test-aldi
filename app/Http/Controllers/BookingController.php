<?php

namespace App\Http\Controllers;

use Acme\Booking\Event\Booking\BookingCreated;
use Acme\Booking\Event\Booking\BookingUpdated;
use Acme\Booking\Model\Booking;
use Acme\Booking\Repository\BookingRepository;
use Acme\Booking\Service\Booking\BookingCreator;
use Acme\Booking\Service\Booking\BookingUpdater;
use Acme\Booking\Service\Booking\RoomValidator;
use Acme\Room\Finder\RoomFinder;
use App\Http\Requests\BookingFormRequest;
use App\Http\Resources\JsonResource;
use Carbon\Carbon;
use LogicException;

class BookingController extends Controller
{
    public function __construct(
        private BookingRepository $repository,
        private BookingCreator    $creator,
        private BookingUpdater    $updater,
        private RoomFinder        $roomFinder,
        private RoomValidator     $validator,
    ) {}

    public function store(BookingFormRequest $request)
    {
        $payload = $request->validated();

        $room = $this->roomFinder->findAndLockOrFail($payload['room_id']);
        $startsAt = Carbon::createFromFormat('Y-m-d', $payload['starts_at']);
        $endsAt = Carbon::createFromFormat('Y-m-d', $payload['ends_at']);

        if (!$this->validator->doesRoomHaveCapacity($room, $startsAt, $endsAt)) {
            throw new LogicException('The booking date(s) is not available.', 400);
        }

        $booking = $this->creator->create($room, $startsAt, $endsAt);

        event(BookingCreated::createFromBooking($booking));

        $this->repository->save($booking);

        return new JsonResource($booking);
    }

    public function update(BookingFormRequest $request, Booking $booking)
    {
        $previousBooking = $booking->replicate();
        $payload = $request->validated();

        $room = $this->roomFinder->findAndLockOrFail($payload['room_id']);
        $startsAt = Carbon::createFromFormat('Y-m-d', $payload['starts_at']);
        $endsAt = Carbon::createFromFormat('Y-m-d', $payload['ends_at']);

        if (!$this->validator->doesRoomHaveCapacity($room, $startsAt, $endsAt, $booking->getKey())) {
            throw new LogicException('The booking date(s) is not available.', 400);
        }

        $booking = $this->updater->update($booking, $room, $startsAt, $endsAt);

        $this->repository->save($booking);

        event(BookingUpdated::createFromBooking($booking, $previousBooking));

        return new JsonResource($booking);
    }
}
