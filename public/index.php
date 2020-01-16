<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathDetector;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/..');
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$basePathDetector = new BasePathDetector($_SERVER);
$app->setBasePath($basePathDetector->getBasePath());


// Set up database connection
require __DIR__ . '/../src/database.php';

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Load utility function
require __DIR__ . '/../src/utils.php';


$app->get('/', 'PublicController:Hello');
$app->get('/outlets', 'PublicController:SearchOutlets');

$app->run();