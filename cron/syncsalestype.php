<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/utils.php';

$url = "http://backoffice.marimakan.co.id/moka/sales_type";
$headers = [
	'Content-Length' => 0
];
echo json_encode(guzzlePOST($url, $headers));