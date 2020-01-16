<?php
use Psr\Container\ContainerInterface as ContainerInterface;

$container->set('PublicController', function(ContainerInterface $c) {
	return new \Controllers\PublicController();
});