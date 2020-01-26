<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathDetector;

require __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set("Asia/Jakarta");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/..');
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
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
$app->get('/moka/businesses', 'PublicController:GetMokaBusinessProfile');
$app->get('/moka/outlets', 'PublicController:SearchMokaOutlets');

$app->post('/moka/outlets', 'PublicController:SyncMokaOutlets');
$app->post('/moka/categories', 'PublicController:SyncMokaCategories');
$app->post('/moka/sales_type', 'PublicController:SyncMokaSalesType');
$app->post('/moka/items', 'PublicController:SyncMokaItems');
$app->post('/moka/transactions', 'PublicController:SyncMokaLatestTransactions');

$app->run();