<?php

namespace PlicniTeplice\Recipes\Api\Actions\Recipes;

use PlicniTeplice\Recipes\Api\Core\RecipeStatus;
use PlicniTeplice\Recipes\Api\Services\RecipeService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class EditRecipeAction extends RecipeAction
{

    public function __construct(ContainerInterface $container, RecipeService $recipeService) {
        parent::__construct($container, $recipeService);
    }

    protected function action(): Response {
        $this->recipeService->changeStatus($this->getArg('id'), RecipeStatus::CLOSED);
        return $this->respond(200);
    }
}