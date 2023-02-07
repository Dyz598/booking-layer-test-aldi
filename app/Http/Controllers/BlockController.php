<?php

namespace App\Http\Controllers;

use Acme\Booking\Event\Block\BlockCreated;
use Acme\Booking\Event\Block\BlockUpdated;
use Acme\Booking\Model\Block;
use Acme\Booking\Repository\BlockRepository;
use Acme\Booking\Service\Block\BlockCreator;
use Acme\Booking\Service\Block\BlockUpdater;
use Acme\Booking\Service\Booking\RoomValidator;
use Acme\Room\Finder\RoomFinder;
use App\Http\Requests\BlockFormRequest;
use App\Http\Resources\JsonResource;
use Carbon\Carbon;
use LogicException;

class BlockController extends Controller
{
    public function __construct(
        private BlockRepository $repository,
        private BlockCreator    $creator,
        private BlockUpdater    $updater,
        private RoomFinder      $roomFinder,
        private RoomValidator   $validator,
    ) {}

    public function store(BlockFormRequest $request)
    {
        $payload = $request->validated();

        $room = $this->roomFinder->findAndLockOrFail($payload['room_id']);
        $startsAt = Carbon::createFromFormat('Y-m-d', $payload['starts_at']);
        $endsAt = Carbon::createFromFormat('Y-m-d', $payload['ends_at']);

        if (!$this->validator->doesRoomHaveCapacity($room, $startsAt, $endsAt)) {
            throw new LogicException('The date(s) cannot be block because they have been occupied.', 400);
        }

        $block = $this->creator->create($room, $startsAt, $endsAt);

        $this->repository->save($block);

        event(BlockCreated::createFromBlock($block));

        return new JsonResource($block);
    }

    public function update(BlockFormRequest $request, Block $block)
    {
        $previousBlock = $block->replicate();
        $payload = $request->validated();

        $room = $this->roomFinder->findAndLockOrFail($payload['room_id']);
        $startsAt = Carbon::createFromFormat('Y-m-d', $payload['starts_at']);
        $endsAt = Carbon::createFromFormat('Y-m-d', $payload['ends_at']);

        $roomHasCapacity = $this->validator->doesRoomHaveCapacity(
            room          : $room,
            startsAt      : $startsAt,
            endsAt        : $endsAt,
            excludeBlockId: $block->getKey(),
        );

        if (!$roomHasCapacity) {
            throw new LogicException('The date(s) cannot be block because they have been occupied.', 400);
        }

        $block = $this->updater->update($block, $room, $startsAt, $endsAt);

        $this->repository->save($block);

        event(BlockUpdated::createFromBlock($block, $previousBlock));

        return new JsonResource($block);
    }
}
