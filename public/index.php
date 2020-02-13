<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathDetector;
use Slim\Exception\HttpNotFoundException;

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

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


$app->get('/', 'PublicController:Hello');
$app->get('/outlets', 'PublicController:SearchOutlets');
$app->get('/items_per_sales_category', 'PublicController:ReportItemBySalesCategory');
$app->get('/moka/businesses', 'PublicController:GetMokaBusinessProfile');
$app->get('/moka/outlets', 'PublicController:SearchMokaOutlets');

$app->post('/portal_visitor', 'PublicController:PortalVisitor');

$app->post('/moka/outlets', 'PublicController:SyncMokaOutlets');
$app->post('/moka/categories', 'PublicController:SyncMokaCategories');
$app->post('/moka/sales_type', 'PublicController:SyncMokaSalesType');
$app->post('/moka/items', 'PublicController:SyncMokaItems');
$app->post('/moka/transactions', 'PublicController:SyncMokaLatestTransactions');

/**
 * Catch-all route to serve a 404 Not Found page if none of the routes match
 * NOTE: make sure this route is defined last
 */
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

$app->run();