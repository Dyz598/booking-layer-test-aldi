<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Repository;

use Component\Repository\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws \ReflectionException
     */
    public function testSuccess(): void
    {
        $repository = new Repository(Room::class);

        $this->assertTrue($repository->findAll() instanceof Collection);
        $this->assertNull($repository->findById(1));

        $room = new Room();
        $room->id = 1;
        $room->capacity = 10;

        $this->assertNotNull($repository->save($room));
        $this->assertNotNull($repository->findById(1));
        $this->assertNotNull($repository->findByIdAndLock(1));

        $query = $this->runInaccessibleMethod($repository, 'createRawQueryBuilder');

        $this->assertTrue($query instanceof Builder);
    }
}
