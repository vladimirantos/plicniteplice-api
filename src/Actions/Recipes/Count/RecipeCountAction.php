<?php

namespace PlicniTeplice\Recipes\Api\Actions\Recipes\Count;

use PlicniTeplice\Recipes\Api\Actions\Recipes\RecipeAction;
use PlicniTeplice\Recipes\Api\Core\RecipeStatus;
use PlicniTeplice\Recipes\Api\Services\RecipeService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class RecipeCountAction extends RecipeAction
{
    private ?string $status;

    public function __construct(ContainerInterface $container, RecipeService $recipeService, ?string $status) {
        parent::__construct($container, $recipeService);
        $this->status = $status;
    }

    protected function action(): Response {
        return $this->respondWithData($this->recipeService->count($this->status) ?? 0);
    }
}