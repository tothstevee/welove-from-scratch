<?php

error_reporting(E_ALL);
  ini_set('display_errors',1);

require_once __DIR__."/../vendor/autoload.php";

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
	'database' => [
		'host' => $_ENV['DB_HOST'],
		'port' => $_ENV['DB_PORT'],
		'database' => $_ENV['DB_DATABASE'],
		'username' => $_ENV['DB_USERNAME'],
		'password' => $_ENV['DB_PASSWORD']
	]
];

$application = new App\Core\Application(dirname(__DIR__), $config);

$application->router->get('/', [\App\Controller\ListController::class,'render']);

$application->router->get('/project', [\App\Controller\ProjectController::class,'render']);
$application->router->post('/project/save', [\App\Controller\ProjectController::class, 'save']);
$application->router->post('/project/delete', [\App\Controller\ProjectController::class, 'delete']);

$application->run();