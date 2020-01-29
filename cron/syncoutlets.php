<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/utils.php';

$url = "http://backoffice.marimakan.co.id/moka/outlets";
echo json_encode(guzzlePOST($url));