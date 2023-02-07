<?php

declare(strict_types=1);

namespace Component\Module;

use Illuminate\Support\ServiceProvider;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
abstract class AbstractModule extends ServiceProvider
{
    public function register()
    {
        foreach ($this->registerRepositories() as $modelClass => $repositoryClass) {
            $this->app->singleton($repositoryClass, function () use ($modelClass, $repositoryClass) {
                return new $repositoryClass($modelClass);
            });
        }
    }

    protected function registerRepositories(): array
    {
        return [];
    }
}
