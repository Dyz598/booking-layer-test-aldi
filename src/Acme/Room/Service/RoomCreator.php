<?php

declare(strict_types=1);

namespace Acme\Room\Service;

use Acme\Room\Model\Room;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomCreator
{
    public function create(int $capacity): Room
    {
        return Room::create($capacity);
    }
}
