<?php

use DI\ContainerBuilder;
use Slim\App;
use Dotenv\Dotenv;
use Tuupola\Middleware\JwtAuthentication;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/container.php');
$container = $containerBuilder->build();

// Create App instance
$app = $container->get(App::class);
$app->add(new JwtAuthentication([
	"path" => "/api", /* or ["/api", "/admin"] */
	"ignore" => ["/api/public/"],
	"attribute" => "current_user",
	"secret" => $_ENV['JWT_SECRET_TOKEN'],
	"algorithm" => ["HS256"],
	"logger" => $container->get(\Psr\Log\LoggerInterface::class),
	"secure" => false, //remove in debug
	"error" => function ($response, $arguments) {
		$data["status"] = "error";
		$data["message"] = $arguments["message"];
		return $response
			->withHeader("Content-Type", "application/json")
			->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	},
	"callback" => function ($options) use ($app) {
		$app->jwt = $options["decoded"];
	}
]));
return $app;
