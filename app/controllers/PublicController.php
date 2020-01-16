<?php
namespace Controllers;

use Models\Outlet;

class PublicController {

	public function __construct() {
		
	}

	public function Hello($request, $response, $args) {
		$response->getBody()->write('Hello World');
    	return $response;
	}

	public function SearchOutlets($request, $response, $args) {
		$filteredOutlets = Outlet::findOutlets();

		$data['outlets'] = $filteredOutlets; 
		$payload = json_encode($data);
		$response->getBody()->write($payload);
		return $response
				->withHeader('Content-Type', 'application/json')
				->withStatus(200);
	}
}