<?php

declare(strict_types=1);

namespace Acme\Room\Finder;

use Acme\Room\Model\Room;
use Acme\Room\Repository\RoomRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
final class RoomFinder
{
    public function __construct(
        private RoomRepository $repository,
    ) {}

    public function findAndLockOrFail(int $id): Room
    {
        if (null !== $room = $this->repository->findByIdAndLock($id)) {
            return $room;
        }

        throw new ModelNotFoundException(
            sprintf('Room with id \'%s\' not found.', $id),
            404
        );
    }
}
