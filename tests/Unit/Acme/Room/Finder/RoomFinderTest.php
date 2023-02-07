<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Room\Finder;

use Acme\Room\Finder\RoomFinder;
use Acme\Room\Model\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomFinderTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccess(): void
    {
        $room = Room::create(10);
        $room->save();

        /** @var RoomFinder $finder */
        $finder = $this->app->make(RoomFinder::class);

        $this->assertNotNull($finder->findAndLockOrFail($room->id));
    }

    public function testFail(): void
    {
        $this->expectException(ModelNotFoundException::class);

        /** @var RoomFinder $finder */
        $finder = $this->app->make(RoomFinder::class);

        $finder->findAndLockOrFail(1);
    }
}
