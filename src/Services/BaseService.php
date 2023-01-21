<?php

namespace PlicniTeplice\Recipes\Api\Services;

use Envms\FluentPDO\Query;
use Ramsey\Uuid\Uuid;

abstract class BaseService
{
    protected Query $query;

    public function __construct(Query $query) {
        $this->query = $query;
    }

    protected function createId(): string{
        return Uuid::uuid4()->toString();
    }
}