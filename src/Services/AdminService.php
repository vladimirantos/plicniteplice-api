<?php

namespace PlicniTeplice\Recipes\Api\Services;

use Envms\FluentPDO\Query;

class AdminService extends BaseService
{
    private const table = 'admin';

    public function __construct(Query $query) {
        parent::__construct($query);
    }

    public function getByEmail(string $email){
        return $this->query->from(self::table)
            ->where(['email' => $email])
            ->select('id, name, email, password', true)
            ->fetch();
    }
}