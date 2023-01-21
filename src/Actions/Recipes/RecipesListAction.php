<?php

namespace PlicniTeplice\Recipes\Api\Actions\Recipes;

use PlicniTeplice\Recipes\Api\Services\RecipeService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class RecipesListAction extends RecipeAction
{
    private string $status;
    public function __construct(ContainerInterface $container, RecipeService $recipeService, string $status) {
        parent::__construct($container, $recipeService);
        $this->status = $status;
    }

    protected function action(): Response {
        return $this->respondWithData($this->recipeService->getRecipes($this->status));
    }
}