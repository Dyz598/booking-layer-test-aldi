<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Module;

use Component\Module\AbstractModule;
use Tests\TestCase;
use Tests\Unit\Component\Module\Repository\SampleRepository;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class AbstractModuleTest extends TestCase
{
    public function testRegisterRepositories(): void
    {
        $stub = $this->getMockForAbstractClass(AbstractModule::class, [$this->app]);
        $result = $this->runInaccessibleMethod($stub, 'registerRepositories');

        $this->assertCount(0, $result);
    }

    public function testRegister(): void
    {
        $module = new SampleModule($this->app);

        $module->register();

        $this->assertNotNull($this->app->make(SampleRepository::class));
    }
}
