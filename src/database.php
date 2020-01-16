<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher as Dispatcher;


$capsule = new Capsule;
$capsule->addConnection([
	'driver' 	=> 'mysql',
 	'host' 		=> getenv('DB_HOST'),
 	'database' 	=> getenv('DB_NAME'),
 	'username' 	=> getenv('DB_USER'),
 	'password' 	=> getenv('DB_PASS'),
 	'charset' 	=> 'utf8',
 	'collation' => 'utf8_unicode_ci',
 	'prefix' 	=> '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$container->set('db', function () use ($capsule){
	return $capsule;
});