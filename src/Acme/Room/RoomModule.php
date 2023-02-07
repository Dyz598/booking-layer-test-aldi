<?php

declare(strict_types=1);

namespace Acme\Room;

use Acme\Room\Model\Room;
use Acme\Room\Repository\RoomRepository;
use Component\Module\AbstractModule;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomModule extends AbstractModule
{
    protected function registerRepositories(): array
    {
        return [
            Room::class => RoomRepository::class,
        ];
    }
}
