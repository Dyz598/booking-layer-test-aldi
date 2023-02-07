<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Model;

use Component\Model\AbstractModel;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class AbstractModelTest extends TestCase
{
    public function testSetPropertyAndGetPropertySuccess(): void
    {
        $stub = $this->getMockForAbstractClass(AbstractModel::class);

        $stub->some_field = 12345;

        $this->assertEquals($stub->someField, 12345);
    }
}
