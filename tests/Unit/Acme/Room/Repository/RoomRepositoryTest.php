<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\Room\Repository;

use Acme\Room\Repository\RoomRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSumCapacity(): void
    {
        DB::table('rooms')->insert(['id' => 1, 'capacity' => 10]);
        DB::table('rooms')->insert(['id' => 2, 'capacity' => 5]);

        /** @var RoomRepository $repo */
        $repo = $this->app->make(RoomRepository::class);

        $result = $repo->sumCapacity([1, 2]);

        $this->assertEquals(15, $result);
    }
}
