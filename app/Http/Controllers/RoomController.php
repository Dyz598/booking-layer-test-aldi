<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Acme\Room\Model\Room;
use Acme\Room\Repository\RoomRepository;
use Acme\Room\Service\RoomCreator;
use App\Http\Requests\RoomFormRequest;
use App\Http\Resources\JsonResource;
use App\Http\Resources\ResourceCollection;

class RoomController extends Controller
{
    public function __construct(
        protected RoomRepository $repository,
        protected RoomCreator    $creator,
    ) {}

    public function index()
    {
        return new ResourceCollection($this->repository->findAll());
    }

    public function store(RoomFormRequest $request)
    {
        $payload = $request->validated();

        $room = $this->creator->create($payload['capacity']);

        $this->repository->save($room);

        return new JsonResource($room);
    }

    public function show(Room $room)
    {
        return new JsonResource($room);
    }
}
