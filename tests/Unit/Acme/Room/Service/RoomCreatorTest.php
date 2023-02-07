<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Room\Service;

use Acme\Room\Model\Room;
use Acme\Room\Service\RoomCreator;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomCreatorTest extends TestCase
{
    public function testSuccess(): void
    {
        /** @var RoomCreator $creator */
        $creator = $this->app->make(RoomCreator::class);

        $room = $creator->create(10);

        $this->assertNotNull($room);
        $this->assertInstanceOf(Room::class, $room);
    }
}
