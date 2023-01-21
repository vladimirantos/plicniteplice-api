<?php
use DI\ContainerBuilder;
use Envms\FluentPDO\Query;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use PlicniTeplice\Recipes\Api\Actions\Admin\LoginAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\ClosedRecipesListAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\Count\ClosedRecipesCountAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\Count\CreatedRecipesCountAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\Count\TotalRecipesCountAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\CreatedRecipesListAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\CreateRecipeAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\EditRecipeAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\RecipesListAction;
use PlicniTeplice\Recipes\Api\Core\Settings\ISettings;
use PlicniTeplice\Recipes\Api\Services\AdminService;
use PlicniTeplice\Recipes\Api\Services\RecipeService;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Container;
use Slim\Factory\AppFactory;

return [
    // Application settings
    'settings' => fn () => require __DIR__ . '/settings.php',

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Register routes
        (require __DIR__ . '/routes.php')($app);

	    (require __DIR__ . '/middleware.php')($app);
        return $app;
    },



	LoggerInterface::class => function (ContainerInterface $c) {
		$settings = $c->get('settings');
		$loggerSettings = $settings['logger'];
		$logger = new Logger($loggerSettings['name']);

		$processor = new UidProcessor();
		$logger->pushProcessor($processor);

		$handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
		$logger->pushHandler($handler);

		return $logger;
	},

Query::class => function (ContainerInterface $c){
	$mysql = $c->get('settings')->get('mysql');
	$pdo = new PDO("mysql:dbname={$mysql['db_name']};host={$mysql['host']}", $mysql['user'],
		$mysql['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
	return new Query($pdo);
},

AdminService::class => function (ContainerInterface $c){
	return new AdminService($c->get(Query::class));
},

RecipeService::class => function (ContainerInterface $c){
	return new RecipeService($c->get(Query::class));
},

LoginAction::class => function (ContainerInterface $c){
	return new LoginAction($c, $c->get(AdminService::class));
},

CreateRecipeAction::class => function (ContainerInterface $c){
	return new CreateRecipeAction($c, $c->get(RecipeService::class));
},

ClosedRecipesCountAction::class => function (ContainerInterface $c){
	return new ClosedRecipesCountAction($c, $c->get(RecipeService::class));
},

CreatedRecipesCountAction::class => function (ContainerInterface $c){
	return new CreatedRecipesCountAction($c, $c->get(RecipeService::class));
},

TotalRecipesCountAction::class => function (ContainerInterface $c){
	return new TotalRecipesCountAction($c, $c->get(RecipeService::class));
},

ClosedRecipesListAction::class => function (ContainerInterface $c){
	return new ClosedRecipesListAction($c, $c->get(RecipeService::class));
},

CreatedRecipesListAction::class => function (ContainerInterface $c){
	return new CreatedRecipesListAction($c, $c->get(RecipeService::class));
},

EditRecipeAction::class => function (ContainerInterface $c){
	return new EditRecipeAction($c, $c->get(RecipeService::class));
}
];
