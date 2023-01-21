<?php

namespace PlicniTeplice\Recipes\Api\Actions\Recipes;

use PlicniTeplice\Recipes\Api\Actions\Action;
use PlicniTeplice\Recipes\Api\Services\RecipeService;
use Psr\Container\ContainerInterface;

abstract class RecipeAction extends Action
{
    protected RecipeService $recipeService;
    public function __construct(ContainerInterface $container, RecipeService $recipeService) {
        parent::__construct($container);
        $this->recipeService = $recipeService;
    }
}