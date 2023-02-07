<?php

declare(strict_types=1);

namespace Component\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as RawQueryBuilder;
use Illuminate\Support\Facades\DB;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class Repository
{
    public function __construct(protected string $modelClass) {}

    public function findAll(): Collection
    {
        $query = $this->createQueryBuilder();

        return $query->get();
    }

    public function findById($id): mixed
    {
        $query = $this->createQueryBuilder();

        return $query->find($id);
    }

    public function findByIdAndLock($id): mixed
    {
        $query = $this->createQueryBuilder();

        $query->lockForUpdate();

        return $query->find($id);
    }

    public function save(Model $model): Model
    {
        $model->save();

        return $model;
    }

    protected function createQueryBuilder(): Builder
    {
        /** @var Model $model */
        $model = new $this->modelClass;

        return $model->newQuery();
    }

    protected function createRawQueryBuilder(): RawQueryBuilder
    {
        /** @var Model $model */
        $model = new $this->modelClass;

        return DB::table($model->getTable());
    }
}
