<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/utils.php';

$url = "http://backoffice.marimakan.co.id/items_per_sales_category";
$headers = [
	'Content-Length' => 0
];
$body = array(
	"since" => date('Y-m-d'),
	"until" => addDays(date('Y-m-d'), 1)
);
echo json_encode(guzzlePOST($url, $headers, $body));