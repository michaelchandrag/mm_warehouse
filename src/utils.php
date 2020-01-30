<?php
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

function curlPOST($url, $headers=array(), $body) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return isJson($result) ? json_decode($result, true) : $result;
}

function curlGET($url, $headers=array()) {
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_HTTPGET,TRUE);
    curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($curl);
    return isJson($output) ? json_decode($output, true) : $output;
}

function guzzleGET($url, $headers=array()) {
	$client = new Client();
	$response = $client->request('GET', $url, [
		'headers' => $headers
	]);
	return isJson($response->getBody()) ? json_decode($response->getBody(), true) : $response->getBody();
}

function guzzlePOST($url, $headers=array(), $body=array()) {
	$client = new Client();
	$response = $client->request('POST', $url, [
		'headers' => $headers,
		'json' => $body
	]);
	return isJson($response->getBody()) ? json_decode($response->getBody(), true) : $response->getBody();
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function throwJSON($response, $data) {
	$payload = json_encode($data);
	$response->getBody()->write($payload);
	return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus(200);
}

function slackWebhook($message) {
    $url = getenv('SLACK_WEBHOOK_URL');
    $payload = array(
    	"text" => $message
    );
    $payload = json_encode($payload);
    return curlPOST($url, array(), $payload);
}

function addDays($date,$add_by)
{
    // append $date to $add_by
    // $date must be a datetime format
    $date = date('Y-m-d',strtotime($date. " + ".$add_by." days"));
    // return date after appended
    return $date;
}

function TZtoDT($tz) {
	$date = new DateTime($tz);
	return $date->format('Y-m-d H:i:s');
}

?>