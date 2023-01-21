<?php

namespace PlicniTeplice\Recipes\Api\Services;

use Envms\FluentPDO\Query;
use PDO;
use PlicniTeplice\Recipes\Api\Core\Crypto;
use Ramsey\Uuid\Uuid;

class RecipeService extends BaseService
{
    const table = 'recipes';

    public function __construct(Query $query) {
        parent::__construct($query);
    }

    /**
     * @throws \Envms\FluentPDO\Exception
     */
    public function create(object $recipe){
        $this->query->getPdo()->beginTransaction();
        $items = $recipe->items;
        unset($recipe->items);
        $id = $this->createId();
        $this->query->insertInto(self::table, [
            'id' => $id,
            'user' => $this->encryptUser($recipe)
        ])->execute();
        foreach ($items as $item) {
            $this->query->insertInto('items')
                ->values(['id' => $this->createId(), 'recipe' => $id, 'name' => $item])
                ->execute();
        }
        $this->query->getPdo()->commit();
    }

    public function changeStatus(string $id, string $status){
        $this->query->update(self::table)->set(['status' => $status])->where('id', $id)->execute();
    }

    public function getRecipes(string $status): array{
        $stmt = $this->query->from(self::table . ' as r')
            ->innerJoin('items as i on i.recipe = r.id')
            ->select('r.id, r.user, r.status, r.created, i.id as itemId, i.name', true)
            ->where(['r.status' => $status])
            ->orderBy('r.created');
        $result = [];
        while ($row = $stmt->fetch()) {
            if (!isset($result[$row['id']]))
                $result[$row['id']] = [
                    'id' => $row['id'],
                    'user' => $this->decryptUser($row['user']),
                    'created' => $row['created'],
                    'items' => []
                ];
            $result[$row['id']]['items'][] = [
                'id' => $row['itemId'],
                'name' => $row['name']
            ];
        }
        return array_values($result);
    }

    public function count(?string $status): int {
        if(!isset($status)){
            return $this->query->from(self::table)->count();
        }
        return $this->query->from(self::table)->where(['status' => $status])->count();
    }

    private function encryptUser(object $recipe): string{
        return Crypto::encrypt(json_encode($recipe));
    }

    private function decryptUser(string $user): object{
        return json_decode(Crypto::decrypt($user));
    }
}